@props([
    'product' => []
])

<div class="col-md-3 col-sm-6">
    <div class="product-card">
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
            ⭐ {{ $product['rating'] ?? '4.5' }}
        </div>
        <h6 class="mt-2">{{ $product['name'] ?? 'Product' }}</h6>
        <div>
            <span class="price">₹{{ $product['final_price'] ?? $product['total_price'] ?? $product['price'] ?? '0.00' }}</span>
            @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
                <span class="old-price ms-2">₹{{ $product['price'] ?? $product['product_price'] ?? '' }}</span>
            @endif
        </div>
        @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
            <div class="offer">Save {{ $product['discount_rate'] }}%</div>
        @else
            <div class="offer">&nbsp;</div>
        @endif
        <div class="d-grid gap-2 mt-3">
            <button class="btn btn-cart" onclick="addToCart({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Add to Cart</button>
            <button class="btn btn-buy" onclick="buyNow({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Buy Now</button>
        </div>
    </div>
</div>
