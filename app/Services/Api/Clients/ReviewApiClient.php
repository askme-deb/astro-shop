<?php

namespace App\Services\Api\Clients;

class ReviewApiClient extends BaseApiClient
{
    /**
     * Create a review for a product, course, or astrologer.
     *
     * @param array $data
     * @return array
     */
    /**
     * @param array $data
     * @param string|null $token
     * @return array
     */
    public function createReview(array $data, ?string $token = null): array
    {
        $options = [
            'json' => $data
        ];
        if ($token) {
            $options['headers'] = [
                'Authorization' => 'Bearer ' . $token
            ];
        }
        return $this->request('POST', 'reviews', $options);
    }

    public function getReviewSummary(string $reviewableType, int $reviewableId, ?string $token = null): array
    {
        $options = [
            'query' => [
                'reviewable_type' => $reviewableType,
                'reviewable_id' => $reviewableId,
            ],
        ];

        if ($token) {
            $options['headers'] = [
                'Authorization' => 'Bearer ' . $token,
            ];
        }

        return $this->request('GET', 'reviews/summary', $options);
    }
}
