@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Shopping Cart</h2>
    
    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @endif

    <div id="cart-container">
        @if(isset($cart) && isset($cart['items']) && count($cart['items']) > 0)
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart['items'] as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item['product']['image_url'] ?? '/assets/images/product-1.jpg' }}" 
                                         alt="{{ $item['product']['name'] ?? 'Product' }}" 
                                         class="rounded me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0">{{ $item['product']['name'] ?? 'Unknown Product' }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>₹{{ number_format($item['price'] ?? 0, 2) }}</td>
                            <td>
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <button class="btn btn-outline-secondary" onclick="updateItemQty('{{ $item['product_id'] }}', {{ $item['quantity'] - 1 }})">-</button>
                                    <input type="text" class="form-control text-center" value="{{ $item['quantity'] }}" readonly>
                                    <button class="btn btn-outline-secondary" onclick="updateItemQty('{{ $item['product_id'] }}', {{ $item['quantity'] + 1 }})">+</button>
                                </div>
                            </td>
                            <td>₹{{ number_format(($item['price'] ?? 0) * $item['quantity'], 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="removeCartItem('{{ $item['product_id'] }}')"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4>Total: ₹{{ number_format($cart['total_price'] ?? 0, 2) }}</h4>
                <a href="/checkout" class="btn btn-dark btn-lg">Proceed to Checkout</a>
            </div>
        @else
            <p class="text-center text-muted">Your cart is empty.</p>
            <div class="text-center mt-3">
                <a href="/products" class="btn btn-primary">Start Shopping</a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function updateItemQty(productId, qty) {
        if (qty < 1) return;
        setLoadingState(true);
        fetch('/api/cart/add-to-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'include',
            body: JSON.stringify({ product_id: productId, quantity: qty, update: true }) 
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                window.location.reload(); 
            } else {
                alert('Failed to update cart');
                setLoadingState(false);
            }
        })
        .catch(err => {
            console.error(err);
            setLoadingState(false);
        });
    }

    function removeCartItem(productId) {
        if(!confirm('Remove this item?')) return;
        setLoadingState(true);
        fetch('/api/cart/add-to-cart', { 
            method: 'POST',
             headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'include',
            body: JSON.stringify({ product_id: productId, quantity: 0 }) 
        })
        .then(response => response.json())
        .then(data => {
             if(data.success) {
                window.location.reload();
            } else {
                alert('Failed to remove item');
                setLoadingState(false);
            }
        })
        .catch(err => {
            console.error(err);
            setLoadingState(false);
        });
    }

    function setLoadingState(loading) {
        const container = document.getElementById('cart-container');
        if(loading) {
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
        } else {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        }
    }
</script>
@endpush
@endsection