<?php

namespace App\Services\Api\Clients;

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
}
