<?php

namespace App\Services;

use App\Services\Api\Clients\ReviewApiClient;

class ReviewService
{
    protected ReviewApiClient $client;

    public function __construct(ReviewApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create a review for a product, course, or astrologer.
     *
     * @param array $data
     * @return array
     */
    public function createReview(array $data, ?string $token = null): array
    {
        return $this->client->createReview($data, $token);
    }

    public function getReviewSummary(string $reviewableType, int $reviewableId, ?string $token = null): array
    {
        return $this->client->getReviewSummary($reviewableType, $reviewableId, $token);
    }
}
