<?php

namespace App\Http\Controllers\Api;

use App\Services\ProductApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

        $page = (int) ($filters['page'] ?? 1);
        $perPage = (int) ($filters['per_page'] ?? 20);
        $filters['per_page'] = $perPage;

        // Call the correct external API endpoint for product search
        $response = $this->productApiService->searchProductsWithFilters($filters);

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
            'products' => $products,
            'pagination' => $pagination,
        ]);
    }
}
