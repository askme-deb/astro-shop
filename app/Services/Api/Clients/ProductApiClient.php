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
}
