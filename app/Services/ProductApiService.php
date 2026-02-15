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
}
