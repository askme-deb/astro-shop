<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    protected ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    protected function resolveApiToken(Request $request): ?string
    {
        $authHeader = $request->header('Authorization');

        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }

        if ($request->hasCookie('auth_api_token')) {
            return $request->cookie('auth_api_token');
        }

        if (session('auth.api_token')) {
            return session('auth.api_token');
        }

        return null;
    }

    protected function sanitizeReviewMessage(?string $message, ?string $fallback = null): string
    {
        $fallbackMessage = $fallback ?: 'Unable to submit your review right now. Please try again.';

        if (! is_string($message) || trim($message) === '') {
            return $fallbackMessage;
        }

        $message = trim($message);

        if (preg_match('/\{.*\}$/s', $message, $matches) === 1) {
            $decoded = json_decode($matches[0], true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $decodedMessage = $decoded['message'] ?? $decoded['error'] ?? null;

                if (is_string($decodedMessage) && trim($decodedMessage) !== '') {
                    return trim($decodedMessage);
                }
            }
        }

        return $message;
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'reviewable_type' => 'required|string',
            'reviewable_id' => 'required|integer|min:1',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|min:5|max:255',
            'comment' => 'required|string|min:20|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $this->sanitizeReviewMessage($validator->errors()->first(), 'Please check the submitted review details.'),
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // Extract token: Authorization header > cookie > session
        $token = $this->resolveApiToken($request);

        $result = $this->reviewService->createReview($validated, $token);

        if (empty($result['status'])) {
            return response()->json([
                'status' => false,
                'message' => $this->sanitizeReviewMessage($result['message'] ?? null),
                'reason' => $result['reason'] ?? null,
                'errors' => $result['errors'] ?? null,
            ], (int) ($result['status_code'] ?? 422));
        }

        return response()->json([
            'status' => true,
            'message' => $this->sanitizeReviewMessage($result['message'] ?? null, 'Review submitted successfully and is pending approval.'),
            'data' => $result['data'] ?? null,
        ], 201);
    }

    public function summary(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reviewable_type' => 'required|string|in:product,course,astrologer',
            'reviewable_id' => 'required|integer',
        ]);

        $result = $this->reviewService->getReviewSummary(
            $validated['reviewable_type'],
            (int) $validated['reviewable_id'],
            $this->resolveApiToken($request)
        );

        if (!empty($result['error'])) {
            return response()->json([
                'status' => false,
                'error' => $result['error'],
            ], 422);
        }

        $summary = $result['data'] ?? $result;

        if (is_array($summary) && isset($summary['data']) && is_array($summary['data'])) {
            $summary = $summary['data'];
        }

        return response()->json([
            'status' => true,
            'total_reviews' => (int) ($summary['total_reviews'] ?? 0),
            'average_rating' => (float) ($summary['average_rating'] ?? 0),
            'data' => $summary,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reviewable_type' => 'required|string|in:product,course,astrologer',
            'reviewable_id' => 'required|integer',
        ]);

        $result = $this->reviewService->getReviews(
            $validated['reviewable_type'],
            (int) $validated['reviewable_id'],
            $this->resolveApiToken($request)
        );

        if (empty($result['status'])) {
            return response()->json([
                'status' => false,
                'message' => $this->sanitizeReviewMessage($result['message'] ?? null, 'Unable to load reviews right now.'),
                'data' => [],
            ], (int) ($result['status_code'] ?? 422));
        }

        return response()->json([
            'status' => true,
            'data' => $result['data'] ?? [],
        ]);
    }
}
