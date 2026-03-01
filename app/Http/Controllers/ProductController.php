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
        $categorySlug = $request->query('category');

        try {
            if ($categorySlug) {
                $items = $this->productApiService->getCategoryWiseProducts($categorySlug, false, ['page' => $page]);
                $products = $items;
                $pagination = [];
            } else {
                $result = $this->productApiService->getPaginatedProducts($page);
                $products = $result['items'];
                $pagination = $result['meta'];
            }
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
        $coupons = [];
        $relatedProducts = [];
        try {
            $products = $this->productApiService->getProducts();
            $product = collect($products)->firstWhere('slug', $slug);
            if ($product && isset($product['id'])) {
                $couponService = app(\App\Services\Api\ProductCouponService::class);
                $coupons = $couponService->getProductCoupons(
                    (int) $product['id'],
                    $product['category_id'] ?? null,
                    ($product['product_price'] ?? 0) - ($product['discount_price'] ?? 0)
                );
                $relatedProducts = $this->productApiService->getRelatedProducts($product['id']);
            }
        } catch (\Throwable $exception) {
            Log::error('Failed to load product detail', [
                'slug' => $slug,
                'message' => $exception->getMessage(),
            ]);
        }
       // dd($relatedProducts);
        return view('products.show', [
            'product' => $product,
            'slug' => $slug,
            'coupons' => $coupons,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * Display products for a specific category by slug (root route).
     */
    public function category(string $category): View
    {
        $products = [];
        $pagination = [];
        $page = 1;
        try {
            $products = $this->productApiService->getCategoryWiseProducts($category, false, ['page' => $page]);
        } catch (\Throwable $exception) {
            Log::error('Failed to load category products', [
                'category' => $category,
                'message' => $exception->getMessage(),
            ]);
        }
        return view('products.index', [
            'products' => $products,
            'pagination' => $pagination,
            'category' => $category,
        ]);
    }
}
