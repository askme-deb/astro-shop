<?php

namespace App\Http\Controllers;

use App\Services\Api\CategoryApiService;
use App\Services\ProductApiService;

class HomeController extends Controller
{
    public function __construct(
        private readonly CategoryApiService $categoryApiService,
        private readonly ProductApiService $productApiService,
    )
    {
    }

    public function index()
    {
        $gemstoneCategories = $this->categoryApiService->getGemstoneCategories('gemstone-1');
        $bestSellingProducts = $this->productApiService->getBestSellingProducts();
        $featuredProducts = $this->productApiService->getFeaturedProducts();

        // Try to load products for the given gemstone category; if the
        // external API returns nothing, fall back to the gemstone
        // categories list you already have (the array you shared).
        $gemstoneCategoryProducts = $this->productApiService->getCategoryWiseProducts('gemstone-1');
        if (empty($gemstoneCategoryProducts)) {
            $gemstoneCategoryProducts = $gemstoneCategories;
        }

        return view('home', [
            'gemstoneCategories' => $gemstoneCategories,
            'bestSellingProducts' => $bestSellingProducts,
            'featuredProducts' => $featuredProducts,
            'gemstoneCategoryProducts' => $gemstoneCategoryProducts,
        ]);
    }
}
