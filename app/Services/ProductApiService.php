<?php

namespace App\Services;

use App\Services\Api\Clients\ProductApiClient;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * ProductApiService contains business logic for working with external products.
 * It coordinates caching, validation, and mapping between the API client and
 * the rest of the application. Controllers should depend on this service
 * rather than talking to HTTP clients directly.
 */
class ProductApiService
{

    protected ProductApiClient $client;

    protected CacheRepository $cache;

    protected int $cacheTtlSeconds;

    public function __construct(ProductApiClient $client)
    {
        $this->client = $client;
        $this->cache = Cache::store();
        $this->cacheTtlSeconds = (int) config('services.astrorajumaharaj.cache_ttl', 300);
    }

    /**
     * Retrieve a single product by ID, with caching.
     *
     * @param int|string $id
     * @param bool $forceRefresh
     * @return array<string, mixed>|null
     */
    public function getProductById($id, bool $forceRefresh = false): ?array
    {
        $cacheKey = 'astro.product.' . $id;
        if ($forceRefresh) {
            $this->cache->forget($cacheKey);
        }
        return $this->cache->remember($cacheKey, $this->cacheTtlSeconds, function () use ($id) {
            try {
                $product = $this->client->getProductById($id);
            } catch (\Throwable $exception) {
                Log::error('Failed to fetch product by ID from external API', [
                    'service' => static::class,
                    'product_id' => $id,
                    'message' => $exception->getMessage(),
                ]);
                return null;
            }
            return $product;
        });
    }

    /**
     * Retrieve best-selling products from the external API, with caching.
     *
     * @param bool $forceRefresh
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function getBestSellingProducts(bool $forceRefresh = false, array $filters = []): array
    {
        $cacheKey = $this->cacheKeyForBestSelling($filters);

        if ($forceRefresh) {
            $this->cache->forget($cacheKey);
        }

        $items = $this->cache->remember($cacheKey, $this->cacheTtlSeconds, function () use ($filters) {
            try {
                $data = $this->client->getBestSellingProducts($filters);
            } catch (\Throwable $exception) {
                Log::error('Failed to fetch best-selling products from external API', [
                    'service' => static::class,
                    'message' => $exception->getMessage(),
                ]);

                return [];
            }

            $items = [];

            // Handle both wrapped and plain list responses similar to getPaginatedProducts.
            if (isset($data['data']) && is_array($data['data'])) {
                $inner = $data['data'];

                if (isset($inner['data']) && is_array($inner['data'])) {
                    $items = array_values($inner['data']);
                } elseif (array_is_list($inner)) {
                    $items = array_values($inner);
                }
            } elseif (array_is_list($data)) {
                /** @var array<int, array<string, mixed>> $dataList */
                $dataList = $data;
                $items = $dataList;
            }

            return $items;
        });

        if (! is_array($items)) {
            return [];
        }

        /** @var array<int, array<string, mixed>> $items */
        return $items;
    }

    /**
     * Retrieve featured products from the external API, with caching.
     *
     * @param bool $forceRefresh
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function getFeaturedProducts(bool $forceRefresh = false, array $filters = []): array
    {
        $cacheKey = $this->cacheKeyForFeatured($filters);

        if ($forceRefresh) {
            $this->cache->forget($cacheKey);
        }

        $items = $this->cache->remember($cacheKey, $this->cacheTtlSeconds, function () use ($filters) {
            try {
                $data = $this->client->getFeaturedProducts($filters);
            } catch (\Throwable $exception) {
                Log::error('Failed to fetch featured products from external API', [
                    'service' => static::class,
                    'message' => $exception->getMessage(),
                ]);

                return [];
            }

            $items = [];

            // Assume the same wrapping pattern as other product list endpoints:
            // { status, message, data: { current_page, data: [ ...products ], ... } }
            if (isset($data['data']) && is_array($data['data'])) {
                $inner = $data['data'];

                if (isset($inner['data']) && is_array($inner['data'])) {
                    $items = array_values($inner['data']);
                } elseif (array_is_list($inner)) {
                    $items = array_values($inner);
                }
            } elseif (array_is_list($data)) {
                /** @var array<int, array<string, mixed>> $dataList */
                $dataList = $data;
                $items = $dataList;
            }

            return $items;
        });

        if (! is_array($items)) {
            return [];
        }

        /** @var array<int, array<string, mixed>> $items */
        return $items;
    }

    /**
     * Retrieve products for a specific category from the external API,
     * with caching.
     *
     * The category is identified by its slug or key, e.g. "gemstone-1" for
     * the endpoint /categories-wise-products/gemstone-1.
     *
     * @param string $categorySlug
     * @param bool $forceRefresh
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function getCategoryWiseProducts(string $categorySlug, bool $forceRefresh = false, array $filters = []): array
    {
        $cacheKey = $this->cacheKeyForCategoryProducts($categorySlug, $filters);

        if ($forceRefresh) {
            $this->cache->forget($cacheKey);
        }

        $items = $this->cache->remember($cacheKey, $this->cacheTtlSeconds, function () use ($categorySlug, $filters) {
            try {
                $data = $this->client->getCategoryWiseProducts($categorySlug, $filters);
            } catch (\Throwable $exception) {
                // Log::error('Failed to fetch category-wise products from external API', [
                //     'service' => static::class,
                //     'category' => $categorySlug,
                //     'message' => $exception->getMessage(),
                // ]);

                return [];
            }

            $items = [];
            // The category-wise endpoint returns:
            // { status, message, products: { current_page, data: [ ...products ], ... }, category: { ... } }
            if (isset($data['products']) && is_array($data['products'])) {
                $inner = $data['products'];

                if (isset($inner['data']) && is_array($inner['data'])) {
                    $items = array_values($inner['data']);
                } elseif (array_is_list($inner)) {
                    $items = array_values($inner);
                }
            } elseif (isset($data['data']) && is_array($data['data'])) {
                // Fallback to the generic pattern if the structure ever changes.
                $inner = $data['data'];

                if (isset($inner['data']) && is_array($inner['data'])) {
                    $items = array_values($inner['data']);
                } elseif (array_is_list($inner)) {
                    $items = array_values($inner);
                }
            } elseif (array_is_list($data)) {
                /** @var array<int, array<string, mixed>> $dataList */
                $dataList = $data;
                $items = $dataList;
            }

            return $items;
        });

        if (! is_array($items)) {
            return [];
        }

        /** @var array<int, array<string, mixed>> $items */
        return $items;
    }

    /**
     * Retrieve products, using cache for performance. Set $forceRefresh to true
     * when running background syncs or explicit cache invalidation.
     *
     * This returns only the product items (no pagination meta). For pagination
     * details, use getPaginatedProducts().
     *
     * @param bool $forceRefresh
     * @param array<string, mixed> $filters
     *
     * @return array<int, array<string, mixed>>
     */
    public function getProducts(bool $forceRefresh = false, array $filters = []): array
    {
        $result = $this->getPaginatedProducts(1, $forceRefresh, $filters);

        return $result['items'];
    }

    /**
     * Retrieve paginated products and pagination meta information.
     *
     * @param int $page
     * @param bool $forceRefresh
     * @param array<string, mixed> $filters
     *
     * @return array{items: array<int, array<string, mixed>>, meta: array<string, mixed>}
     */
    public function getPaginatedProducts(int $page = 1, bool $forceRefresh = false, array $filters = []): array
    {
        $filtersWithPage = array_merge($filters, ['page' => $page]);
        $cacheKey = $this->cacheKeyForProducts($filtersWithPage);

        if ($forceRefresh) {
            $this->cache->forget($cacheKey);
        }

        $payload = $this->cache->remember($cacheKey, $this->cacheTtlSeconds, function () use ($filtersWithPage) {
            try {
                $data = $this->client->getProducts($filtersWithPage);
            } catch (\Throwable $exception) {
                Log::error('Failed to fetch products from external API', [
                    'service' => static::class,
                    'message' => $exception->getMessage(),
                ]);

                return [
                    'items' => [],
                    'meta' => [],
                ];
            }

            $items = [];
            $meta = [];

            // The Astro API wraps results as:
            // { status, message, data: { current_page, data: [ ...products ], ...paginationMeta } }
            if (isset($data['data']) && is_array($data['data'])) {
                $inner = $data['data'];

                if (isset($inner['data']) && is_array($inner['data'])) {
                    $items = array_values($inner['data']);
                    $meta = $inner;
                    unset($meta['data']);
                } elseif (array_is_list($inner)) {
                    $items = array_values($inner);
                }
            } elseif (array_is_list($data)) {
                /** @var array<int, array<string, mixed>> $dataList */
                $dataList = $data;
                $items = $dataList;
            }

            return [
                'items' => $items,
                'meta' => $meta,
            ];
        });

        // Ensure structure integrity
        if (! isset($payload['items']) || ! is_array($payload['items'])) {
            $payload['items'] = [];
        }

        if (! isset($payload['meta']) || ! is_array($payload['meta'])) {
            $payload['meta'] = [];
        }

        return [
            'items' => $payload['items'],
            'meta' => $payload['meta'],
        ];
    }

    /**
     * Explicitly clear cached products (e.g. after local updates or admin actions).
     *
     * @param array<string, mixed> $filters
     */
    public function clearProductsCache(array $filters = []): void
    {
        $cacheKey = $this->cacheKeyForProducts($filters);

        $this->cache->forget($cacheKey);
    }

    /**
     * Build a deterministic cache key for product listings.
     *
     * @param array<string, mixed> $filters
     */
    protected function cacheKeyForProducts(array $filters = []): string
    {
        if (empty($filters)) {
            return 'astro.products.all';
        }

        ksort($filters);

        return 'astro.products.' . md5(json_encode($filters));
    }

    /**
     * Build a cache key specifically for best-selling products.
     *
     * @param array<string, mixed> $filters
     */
    protected function cacheKeyForBestSelling(array $filters = []): string
    {
        if (empty($filters)) {
            return 'astro.products.best_selling';
        }

        ksort($filters);

        return 'astro.products.best_selling.' . md5(json_encode($filters));
    }

    /**
     * Build a cache key for featured product listings.
     *
     * @param array<string, mixed> $filters
     */
    protected function cacheKeyForFeatured(array $filters = []): string
    {
        if (empty($filters)) {
            return 'astro.products.featured';
        }

        ksort($filters);

        return 'astro.products.featured.' . md5(json_encode($filters));
    }

    /**
     * Build a cache key for category-wise product listings.
     *
     * @param string $categorySlug
     * @param array<string, mixed> $filters
     */
    protected function cacheKeyForCategoryProducts(string $categorySlug, array $filters = []): string
    {
        if (empty($filters)) {
            return 'astro.products.category.' . $categorySlug;
        }

        ksort($filters);

        return 'astro.products.category.' . $categorySlug . '.' . md5(json_encode($filters));
    }

    /**
     * Retrieve related products for a given product ID.
     *
     * @param int|string $productId
     * @param bool $forceRefresh
     * @return array<int, array<string, mixed>>
     */
    public function getRelatedProducts($productId, bool $forceRefresh = false): array
    {
        $cacheKey = 'astro.product.related.' . $productId;
        if ($forceRefresh) {
            $this->cache->forget($cacheKey);
        }
        return $this->cache->remember($cacheKey, $this->cacheTtlSeconds, function () use ($productId) {
            try {
                return $this->client->getRelatedProducts($productId);
            } catch (\Throwable $exception) {
                \Log::error('Failed to fetch related products from external API', [
                    'service' => static::class,
                    'product_id' => $productId,
                    'message' => $exception->getMessage(),
                ]);
                return [];
            }
        });
    }

    /**
     * Search products by query string for autocomplete.
     *
     * @param string $query
     * @return array<int, array<string, mixed>>
     */
    public function searchProducts(string $query): array
    {
        try {
            $response = $this->client->searchProducts($query);
            if (isset($response['data']) && is_array($response['data'])) {
                return array_values($response['data']);
            } elseif (array_is_list($response)) {
                return $response;
            }
        } catch (\Throwable $exception) {
            \Log::error('Product search failed', [
                'service' => static::class,
                'query' => $query,
                'message' => $exception->getMessage(),
            ]);
        }
        return [];
    }
}
