@extends('layouts.app')

@section('title', 'Koszyk - BMCODEX')

@section('styles')
<style>
    .cart-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .cart-items {
        background-color: var(--light-gray);
        padding: 2rem;
        border-radius: 8px;
    }
    
    .cart-item {
        display: grid;
        grid-template-columns: 80px 1fr auto auto;
        gap: 1.5rem;
        align-items: center;
        padding: 1.5rem;
        background-color: var(--dark-bg);
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    
    .cart-item-image {
        width: 80px;
        height: 80px;
        background-color: var(--light-gray);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }
    
    .cart-item-info h3 {
        margin-bottom: 0.5rem;
    }
    
    .cart-item-price {
        font-size: 1.5rem;
        color: var(--primary-color);
        font-weight: 700;
    }
    
    .cart-item-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quantity-control input {
        width: 60px;
        padding: 0.3rem;
        text-align: center;
        background-color: var(--light-gray);
        border: 1px solid var(--primary-color);
        color: var(--text-light);
        border-radius: 4px;
    }
    
    .cart-summary {
        background-color: var(--light-gray);
        padding: 2rem;
        border-radius: 8px;
        height: fit-content;
        position: sticky;
        top: 100px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--dark-bg);
    }
    
    .summary-total {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-top: 1rem;
    }
    
    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
        background-color: var(--light-gray);
        border-radius: 8px;
    }
    
    @media (max-width: 768px) {
        .cart-container {
            grid-template-columns: 1fr;
        }
        
        .cart-item {
            grid-template-columns: 1fr;
            text-align: center;
        }
    }
</style>
@endsection

@section('content')
<h1 style="margin-bottom: 2rem;">🛒 Twój Koszyk</h1>

@if($cartItems && $cartItems->count() > 0)
    <div class="cart-container">
        <div class="cart-items">
            @foreach($cartItems as $item)
                <div class="cart-item">
                    <div class="cart-item-image">🏎️</div>
                    
                    <div class="cart-item-info">
                        <h3>{{ $item->product->name }}</h3>
                        <p style="color: var(--text-gray);">{{ $item->product->category->name }}</p>
                        <p style="color: var(--text-gray); font-size: 0.9rem;">SKU: {{ $item->product->sku }}</p>
                    </div>
                    
                    <div class="cart-item-price">
                        {{ number_format($item->product->price * $item->quantity, 2) }} PLN
                        <p style="font-size: 0.9rem; color: var(--text-gray); font-weight: normal;">
                            {{ number_format($item->product->price, 2) }} PLN / szt.
                        </p>
                    </div>
                    
                    <div class="cart-item-actions">
                        <form action="{{ route('cart.update', $item) }}" method="POST" class="quantity-control">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}">
                            <button type="submit" class="btn btn-secondary" style="padding: 0.3rem 0.8rem;">
                                Aktualizuj
                            </button>
                        </form>
                        
                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary" style="width: 100%;">
                                🗑️ Usuń
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
            
            <form action="{{ route('cart.clear') }}" method="POST" style="margin-top: 1rem;">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    Wyczyść koszyk
                </button>
            </form>
        </div>
        
        <div class="cart-summary">
            <h2 style="margin-bottom: 1rem;">Podsumowanie</h2>
            
            <div class="summary-row">
                <span>Liczba produktów:</span>
                <strong>{{ $cartItems->sum('quantity') }} szt.</strong>
            </div>
            
            <div class="summary-row">
                <span>Wartość produktów:</span>
                <strong>{{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }} PLN</strong>
            </div>
            
            <div class="summary-row">
                <span>Dostawa:</span>
                <strong>GRATIS</strong>
            </div>
            
            <div class="summary-row summary-total">
                <span>Razem:</span>
                <strong>{{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }} PLN</strong>
            </div>
            
            <a href="{{ route('checkout') }}" class="btn btn-primary" style="width: 100%; margin-top: 2rem; padding: 1rem; text-align: center; font-size: 1.2rem;">
                Przejdź do kasy
            </a>
            
            <a href="{{ route('products.index') }}" class="btn btn-secondary" style="width: 100%; margin-top: 1rem; text-align: center;">
                Kontynuuj zakupy
            </a>
        </div>
    </div>
@else
    <div class="empty-cart">
        <h2 style="margin-bottom: 1rem;">Twój koszyk jest pusty 😔</h2>
        <p style="color: var(--text-gray); margin-bottom: 2rem;">
            Dodaj produkty do koszyka, aby kontynuować zakupy
        </p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            Przeglądaj produkty
        </a>
    </div>
@endif
@endsection
