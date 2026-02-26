@extends('layouts.app')

@section('content')
    <div class="container py-5 text-center">
        <h1 class="display-4 mb-3">Thank You!</h1>
        <p class="lead">Your order has been placed successfully.</p>
        @if(session('last_order.id'))
            <p class="mt-3">Your Order ID: <strong>{{ session('last_order.id') }}</strong></p>
        @endif
        <a href="/" class="btn btn-primary mt-4" style="background: #f88500;border: 1px solid #f88500;">Go to Home</a>
    </div>
@endsection
