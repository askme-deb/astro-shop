<?php

namespace App\Services\Api;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Facades\Log;

class CategoryApiService extends BaseApiClient
{
    /**
     * Astro API endpoint for fetching categories.
     *
     * The BaseApiClient base URL already includes the API version.
     * Example full URL: https://admin.astrorajumaharaj.com/api/v1/categories
     */
    protected string $categoriesEndpoint = 'categories';

    /**
     * Fetch gemstone categories using the provided parent slug.
     *
     * @param string $parentSlug
     * @return array<int, array<string, mixed>>
     */
    public function getGemstoneCategories(string $parentSlug = 'gemstone-1'): array
    {
        try {
            $response = $this->request('GET', $this->categoriesEndpoint, [
                'query' => [
                    'parent_slug' => $parentSlug,
                ],
            ]);
        } catch (\Throwable $exception) {
            Log::error('Failed to fetch gemstone categories from external API', [
                'service' => static::class,
                'message' => $exception->getMessage(),
            ]);

            return [];
        }

        // Normalise the payload to an array of category items.
        $items = [];

        if (isset($response['data']) && is_array($response['data'])) {
            // Common Astro API pattern: { status, message, data: [...] }
            $items = array_values($response['data']);
        } elseif (is_array($response) && array_is_list($response)) {
            /** @var array<int, array<string, mixed>> $response */
            $items = $response;
        }

        return $items;
    }
}
