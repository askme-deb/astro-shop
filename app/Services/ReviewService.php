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
     * @param array<string, mixed> $response
     */
    protected function extractFirstErrorMessage(array $response): ?string
    {
        $errors = $response['errors'] ?? null;

        if (! is_array($errors)) {
            return null;
        }

        foreach ($errors as $errorGroup) {
            if (is_array($errorGroup)) {
                foreach ($errorGroup as $message) {
                    if (is_string($message) && trim($message) !== '') {
                        return trim($message);
                    }
                }
            }

            if (is_string($errorGroup) && trim($errorGroup) !== '') {
                return trim($errorGroup);
            }
        }

        return null;
    }

    protected function isTechnicalMessage(?string $message): bool
    {
        if (! is_string($message) || trim($message) === '') {
            return true;
        }

        return (bool) preg_match('/http request returned status code|sqlstate|exception|stack trace|undefined|fatal error|syntax error|<html|server error/i', $message);
    }

    protected function extractReadableMessage(?string $message): ?string
    {
        if (! is_string($message)) {
            return null;
        }

        $message = trim($message);

        if ($message === '') {
            return null;
        }

        if (preg_match('/\{.*\}$/s', $message, $matches) === 1) {
            $decoded = json_decode($matches[0], true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $jsonMessage = $decoded['message'] ?? $decoded['error'] ?? $this->extractFirstErrorMessage($decoded);

                if (is_string($jsonMessage) && trim($jsonMessage) !== '') {
                    return trim($jsonMessage);
                }
            }
        }

        return $this->isTechnicalMessage($message) ? null : $message;
    }

    /**
     * @param array<string, mixed> $response
     */
    protected function humanReadableReviewMessage(array $response): string
    {
        $reason = (string) ($response['reason'] ?? '');
        $message = $response['message'] ?? $response['error'] ?? $this->extractFirstErrorMessage($response);

        $readableMessage = $this->extractReadableMessage(is_string($message) ? $message : null);

        if ($readableMessage !== null) {
            return $readableMessage;
        }

        return match ($reason) {
            'already_reviewed' => 'You have already reviewed this item.',
            'purchase_required', 'not_purchased', 'not_eligible' => 'You need to complete a purchase before reviewing this item.',
            'unauthorized', 'unauthenticated' => 'Please sign in to submit a review.',
            'not_found', 'reviewable_not_found' => 'The item you are trying to review could not be found.',
            default => 'Unable to submit your review right now. Please try again.',
        };
    }

    /**
     * @param array<string, mixed> $response
     */
    protected function reviewErrorStatusCode(array $response): int
    {
        $statusCode = (int) ($response['status_code'] ?? 0);

        if ($statusCode >= 400 && $statusCode < 600) {
            return $statusCode;
        }

        return match ((string) ($response['reason'] ?? '')) {
            'unauthorized', 'unauthenticated' => 401,
            'not_found', 'reviewable_not_found' => 404,
            default => 422,
        };
    }

    /**
     * Create a review for a product, course, or astrologer.
     *
     * @param array $data
     * @return array
     */
    public function createReview(array $data, ?string $token = null): array
    {
        if ($token === null || $token === '') {
            return [
                'status' => false,
                'message' => 'Please sign in to submit a review.',
                'reason' => 'unauthenticated',
                'status_code' => 401,
            ];
        }

        $response = $this->client->createReview($data, $token);

        if (! empty($response['status'])) {
            return [
                'status' => true,
                'message' => is_string($response['message'] ?? null) && trim((string) $response['message']) !== ''
                    ? trim((string) $response['message'])
                    : 'Review submitted successfully and is pending approval.',
                'data' => $response['data'] ?? $response,
            ];
        }

        return [
            'status' => false,
            'message' => $this->humanReadableReviewMessage($response),
            'reason' => $response['reason'] ?? null,
            'errors' => $response['errors'] ?? null,
            'status_code' => $this->reviewErrorStatusCode($response),
        ];
    }

    public function getReviewSummary(string $reviewableType, int $reviewableId, ?string $token = null): array
    {
        return $this->client->getReviewSummary($reviewableType, $reviewableId, $token);
    }

    /**
     * @return array{status: bool, data: array<int, array<string, mixed>>, message?: string, status_code?: int}
     */
    public function getReviews(string $reviewableType, int $reviewableId, ?string $token = null): array
    {
        $response = $this->client->getReviews($reviewableType, $reviewableId, $token);

        if (! empty($response['status']) && isset($response['data'])) {
            $data = $response['data'];

            if (isset($data['data']) && is_array($data['data'])) {
                $data = $data['data'];
            }

            if (is_array($data) && array_is_list($data)) {
                return [
                    'status' => true,
                    'data' => $data,
                ];
            }
        }

        if (array_is_list($response)) {
            return [
                'status' => true,
                'data' => $response,
            ];
        }

        return [
            'status' => false,
            'data' => [],
            'message' => $this->humanReadableReviewMessage($response),
            'status_code' => $this->reviewErrorStatusCode($response),
        ];
    }
}
