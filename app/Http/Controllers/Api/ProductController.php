<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected ProductApiService $productApiService;

    public function __construct(ProductApiService $productApiService)
    {
        $this->productApiService = $productApiService;
    }

    /**
     * Return product details by ID (for API).
     */
    public function show($id): JsonResponse
    {
        $product = $this->productApiService->getProductById($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'error' => 'Product not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $product,
        ]);
    }

    /**
     * Product search for autocomplete.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        if (strlen($query) < 2) {
            return response()->json([
                'status' => false,
                'data' => [],
                'error' => 'Query too short',
            ], 400);
        }
        $results = $this->productApiService->searchProducts($query);
        return response()->json([
            'status' => true,
            'data' => $results,
        ]);
    }
}
