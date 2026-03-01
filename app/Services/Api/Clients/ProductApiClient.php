<?php

namespace App\Services\Api\Clients;

use Illuminate\Support\Facades\Log;

/**
 * ProductApiClient wraps product-related HTTP calls to the Astro Raja Maharaj API.
 * It focuses purely on HTTP concerns and delegates transport behavior to BaseApiClient.
 */
class ProductApiClient extends BaseApiClient
{
    /**
     * Fetch a single product by ID from the external API.
     *
     * @param int|string $id
     * @return array<string, mixed>|null
     */
    public function getProductById($id): ?array
    {
        $endpoint = sprintf('product/details/%s', $id);
        $response = $this->request('GET', $endpoint);
        // The external API returns product data under 'product' key
        if (isset($response['product']) && is_array($response['product'])) {
            return $response['product'];
        }
        // Fallback: If the response is the product itself
        if (isset($response['id'])) {
            return $response;
        }
        return null;
    }
    /**
     * Fetch a list of products from the external API.
     *
     * @param array<string, mixed> $filters
     *
     * @return array<string, mixed>|array<int, mixed>
     */
    public function getProducts(array $filters = []): array
    {
        return $this->request('GET', 'products', [
            'query' => $filters,
        ]);
    }

    /**
     * Fetch a list of best-selling products from the external API.
     *
     * @param array<string, mixed> $filters
     *
     * @return array<string, mixed>|array<int, mixed>
     */
    public function getBestSellingProducts(array $filters = []): array
    {
        return $this->request('GET', 'best-selling-products', [
            'query' => $filters,
        ]);
    }

    /**
     * Fetch a list of featured products from the external API.
     *
     * Endpoint: /featured-products
     *
     * @param array<string, mixed> $filters
     *
     * @return array<string, mixed>|array<int, mixed>
     */
    public function getFeaturedProducts(array $filters = []): array
    {
        return $this->request('GET', 'featured-products', [
            'query' => $filters,
        ]);
    }

    /**
     * Fetch a list of products for a given category from the external API.
     *
     * Example endpoint: categories-wise-products/gemstone-1
     *
     * @param string $categorySlug
     * @param array<string, mixed> $filters
     *
     * @return array<string, mixed>|array<int, mixed>
     */
    public function getCategoryWiseProducts(string $categorySlug, array $filters = []): array
    {
        $endpoint = sprintf('categories-wise-products/%s', $categorySlug);

        $response = $this->request('GET', $endpoint, [
            'query' => $filters,
        ]);

        // Log::info('Category-wise products API response', [
        //     'endpoint' => $endpoint,
        //     'category_slug' => $categorySlug,
        //     'filters' => $filters,
        //     'response' => $response,
        // ]);

        return $response;
    }

    /**
     * Fetch related products for a given product ID from the external API.
     *
     * @param int|string $productId
     * @return array<int, array<string, mixed>>
     */
    public function getRelatedProducts($productId): array
    {
        $endpoint = sprintf('products/%s/related', $productId);
        $response = $this->request('GET', $endpoint);
        if (isset($response['data']) && is_array($response['data'])) {
            return array_values($response['data']);
        } elseif (array_is_list($response)) {
            return $response;
        }
        return [];
    }

    /**
     * Search products from external API for autocomplete.
     *
     * @param string $query
     * @return array<int, array<string, mixed>>
     */
    public function searchProducts(string $query): array
    {
        $response = $this->request('GET', 'product/search', [
            'query' => ['q' => $query],
        ]);
        if (isset($response['data']) && is_array($response['data'])) {
            return array_values($response['data']);
        } elseif (array_is_list($response)) {
            return $response;
        }
        return [];
    }
}
