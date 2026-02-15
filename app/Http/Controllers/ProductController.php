<?php

namespace App\Http\Controllers;

use App\Services\ProductApiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected ProductApiService $productApiService;

    public function __construct(ProductApiService $productApiService)
    {
        $this->productApiService = $productApiService;
    }

    /**
     * Display a listing of products from the external Astro API.
     */
    public function index(Request $request): View
    {
        $products = [];
        $pagination = [];

        $page = (int) $request->query('page', 1);

        try {
            $result = $this->productApiService->getPaginatedProducts($page);
            $products = $result['items'];
            $pagination = $result['meta'];
        } catch (\Throwable $exception) {
            Log::error('Failed to load products for index view', [
                'message' => $exception->getMessage(),
            ]);
        }

        return view('products.index', [
            'products' => $products,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Display a single product by slug.
     */
    public function show(string $slug): View
    {
        $product = null;

        try {
            $products = $this->productApiService->getProducts();
            $product = collect($products)->firstWhere('slug', $slug);
        } catch (\Throwable $exception) {
            Log::error('Failed to load product detail', [
                'slug' => $slug,
                'message' => $exception->getMessage(),
            ]);
        }

        return view('products.show', [
            'product' => $product,
        ]);
    }
}
