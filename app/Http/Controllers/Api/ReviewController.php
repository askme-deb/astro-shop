<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reviewable_type' => 'required|string',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|min:5',
            'comment' => 'required|string|min:10',
        ]);

        // Extract token: Authorization header > cookie > session
        $token = $this->resolveApiToken($request);

        $result = $this->reviewService->createReview($validated, $token);

        if (!empty($result['error']) || !empty($result['message'])) {
            // Only return the user-friendly message
            return response()->json([
                'status' => false,
                'error' => $result['message'] ?? $result['error'] ?? 'Failed to submit review.'
            ], 422);
        }

        return response()->json(['status' => true, 'data' => $result]);
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
}
