@props([
    'product' => []
])

@php
    $caratValue = $product['carat'] ?? null;
    $rattiValue = $product['ratti'] ?? null;
    $hasCaratValue = !is_null($caratValue) && $caratValue !== '' && (float) $caratValue > 0;
    $hasRattiValue = !is_null($rattiValue) && $rattiValue !== '' && (float) $rattiValue > 0;
@endphp

<div class="col-md-3 col-sm-6">
    <div class="product-card" data-product-id="{{ $product['id'] ?? 0 }}">
        <i class="bi {{ (!empty($product['is_in_wishlist']) || !empty($product['in_wishlist'])) ? 'bi-heart-fill' : 'bi-heart' }} wishlist" data-product-id="{{ $product['id'] ?? 0 }}"></i>
        @if(!empty($product['slug']))
            <a href="{{ route('products.show', ['slug' => $product['slug']]) }}">
                <img src="{{ $product['image_url'] ?? asset('assets/images/product-1.jpg') }}" alt="{{ $product['name'] ?? 'Product' }}">
            </a>
        @else
            <a href="#">
                <img src="{{ $product['image_url'] ?? asset('assets/images/product-1.jpg') }}" alt="{{ $product['name'] ?? 'Product' }}">
            </a>
        @endif
        <div class="rating">
            ⭐ {{ $product['rating'] ?? '0.0' }} | {{ $product['reviews_count'] ?? '0' }} Reviews
        </div>
        <h6 class="mt-2">{{ $product['name'] ?? 'Product' }}</h6>
        <div class="price-box mb-3">
            @if(!empty($product['discount_price']) && $product['discount_price'] > 0)
                <span class="price fs-3 fw-bold">₹{{ number_format(($product['product_price'] ?? 0) - ($product['discount_price'] ?? 0), 2) }}</span>
                <span class="old-price ms-2">₹{{ $product['product_price'] }}</span>
                <span class="badge bg-success ms-2">{{ $product['discount_rate'] }}% OFF</span>
            @else
                <span class="price fs-3 fw-bold">₹{{ $product['product_price'] ?? $product['final_price'] ?? $product['price'] ?? '0' }}</span>
                <div class="">&nbsp;</div>
            @endif
        </div>
        @if(!empty($product['sku']))
            <div class="small text-muted mb-1">SKU: {{ $product['sku'] }}</div>
        @endif
        @if(!empty($product['origin_name']))
            <div class="small text-muted mb-1">Origin: {{ $product['origin_name'] }}</div>
        @endif
        @if($hasCaratValue)
            <div class="small text-muted mb-1">Carat: {{ $caratValue }}</div>
        @endif
        @if($hasRattiValue)
            <div class="small text-muted mb-3">Ratti: {{ $rattiValue }}</div>
        @endif
        <div class="d-grid gap-2 mt-3">
            <button class="btn btn-cart" onclick="addToCart({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Add to Cart</button>
            <button class="btn btn-buy" onclick="buyNow({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Buy Now</button>
        </div>
    </div>
</div>
