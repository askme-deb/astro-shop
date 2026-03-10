<?php

namespace App\Http\Controllers\Api;

use App\Services\ProductApiService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductSearchController
{
    protected ProductApiService $productApiService;

    public function __construct(ProductApiService $productApiService)
    {
        $this->productApiService = $productApiService;
    }

    /**
     * Handle AJAX product search/filter requests.
     * Accepts query params: q, brand_id, min_price, max_price, in_stock, sort, per_page, page, etc.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $filters = $request->only([
            'q', 'category_id', 'brand_id', 'min_price', 'max_price', 'in_stock', 'sort', 'per_page', 'page',
            'ratti', 'carat', 'min_ratti', 'max_ratti', 'min_carat', 'max_carat', 'product_grade_id', 'grade_id', 'grade'
        ]);

        $query = trim((string) ($filters['q'] ?? ''));

        if ($query !== '' && strlen($query) < 2) {
            return response()->json([
                'status' => false,
                'data' => [],
                'products' => [],
                'pagination' => [],
                'message' => 'Query too short',
            ], 400);
        }

        // category_id[] support
        if ($request->has('category_id')) {
            $filters['category_id'] = $request->input('category_id');
        }

        // Map sort param to API expected value
        if (isset($filters['sort'])) {
            $sortMap = [
                'price-low' => 'price_low_high',
                'price-high' => 'price_high_low',
                'best' => 'best_selling',
                'new' => 'new_arrivals',
            ];
            $filters['sort'] = $sortMap[$filters['sort']] ?? $filters['sort'];
        }

        $perPage = (int) ($filters['per_page'] ?? 20);
        $filters['per_page'] = $perPage;

        try {
            $response = $this->productApiService->searchProductsWithFilters($filters);
        } catch (RequestException $exception) {
            Log::warning('Product search upstream request failed', [
                'service' => static::class,
                'filters' => $filters,
                'status' => $exception->response?->status(),
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'data' => [],
                'products' => [],
                'pagination' => [],
                'message' => 'Product search is temporarily unavailable.',
            ], 502);
        } catch (\Throwable $exception) {
            Log::error('Product search failed unexpectedly', [
                'service' => static::class,
                'filters' => $filters,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'data' => [],
                'products' => [],
                'pagination' => [],
                'message' => 'Product search is temporarily unavailable.',
            ], 500);
        }

        $products = [];
        $pagination = [];
        if (isset($response['data']) && is_array($response['data'])) {
            $data = $response['data'];
            if (isset($data['data']) && is_array($data['data'])) {
                $products = $data['data'];
                $pagination = $data;
                unset($pagination['data']);
            } elseif (array_is_list($data)) {
                $products = $data;
            }
        } elseif (array_is_list($response)) {
            $products = $response;
        }

        return response()->json([
            'status' => true,
            'data' => $products,
            'products' => $products,
            'pagination' => $pagination,
        ]);
    }
}
