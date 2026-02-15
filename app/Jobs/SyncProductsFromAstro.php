<?php

namespace App\Jobs;

use App\Services\ProductApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * SyncProductsFromAstro is an example queueable job that can be scheduled
 * to keep product data warm in cache or synchronized to a local data store.
 */
class SyncProductsFromAstro implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(ProductApiService $productApiService): void
    {
        try {
            // Force refresh to bypass cache before repopulating it.
            $products = $productApiService->getProducts(true);

            Log::info('Synced products from Astro API', [
                'count' => count($products),
            ]);
        } catch (\Throwable $exception) {
            Log::error('SyncProductsFromAstro job failed', [
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
