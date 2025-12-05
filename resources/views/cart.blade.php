@extends('layouts.app')

@section('title', 'Koszyk - BMCODEX')

@section('styles')
<style>
    .cart-container {
        max-width: 1200px;
        margin: 3rem auto;
        padding: 0 1rem;
    }
    
    .cart-header {
        font-size: 2.5rem;
        margin-bottom: 2rem;
        color: var(--text-light);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 1rem;
    }
    
    .cart-empty {
        text-align: center;
        padding: 4rem 2rem;
        background-color: var(--light-gray);
        border-radius: 8px;
    }
    
    .cart-empty h2 {
        font-size: 2rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    .cart-empty p {
        color: #999;
        margin-bottom: 2rem;
    }
    
    .cart-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 2rem;
    }
    
    .cart-items {
        background-color: var(--light-gray);
        border-radius: 8px;
        padding: 1.5rem;
    }
    
    .cart-item {
        display: grid;
        grid-template-columns: 120px 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem;
        background-color: var(--dark-bg);
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #333;
    }
    
    .cart-item-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 4px;
        border: 2px solid var(--primary-color);
    }
    
    .cart-item-details h3 {
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
        color: var(--text-light);
    }
    
    .cart-item-details .category {
        color: var(--primary-color);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .cart-item-details .price {
        font-size: 1.5rem;
        color: var(--primary-color);
        font-weight: 700;
        margin: 0.5rem 0;
    }
    
    .cart-item-actions {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-end;
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background-color: var(--light-gray);
        border-radius: 4px;
        padding: 0.5rem;
    }
    
    .quantity-controls button {
        width: 30px;
        height: 30px;
        background-color: var(--primary-color);
        color: var(--dark-bg);
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 700;
    }
    
    .quantity-controls input {
        width: 60px;
        text-align: center;
        background-color: var(--dark-bg);
        color: var(--text-light);
        border: 1px solid var(--primary-color);
        border-radius: 4px;
        padding: 0.3rem;
    }
    
    .btn-remove {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-remove:hover {
        background-color: #c82333;
    }
    
    .cart-summary {
        background-color: var(--light-gray);
        border-radius: 8px;
        padding: 2rem;
        height: fit-content;
        position: sticky;
        top: 2rem;
    }
    
    .cart-summary h2 {
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        color: var(--text-light);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 0.5rem;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #333;
        color: var(--text-light);
    }
    
    .summary-row.total {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        border-bottom: none;
        margin-top: 1rem;
    }
    
    .btn-checkout {
        width: 100%;
        padding: 1rem;
        background-color: var(--primary-color);
        color: var(--dark-bg);
        border: none;
        border-radius: 4px;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-checkout:hover {
        background-color: #ff6a33;
        transform: translateY(-2px);
    }
    
    .btn-continue {
        width: 100%;
        padding: 0.8rem;
        background-color: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 1rem;
        transition: all 0.3s ease;
    }
    
    .btn-continue:hover {
        background-color: var(--primary-color);
        color: var(--dark-bg);
    }
    
    @media (max-width: 768px) {
        .cart-content {
            grid-template-columns: 1fr;
        }
        
        .cart-item {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }
        
        .cart-item-actions {
            grid-column: 1 / -1;
            flex-direction: row;
            justify-content: space-between;
        }
        
        .cart-summary {
            position: static;
        }
    }
</style>
@endsection

@section('content')
<div class="cart-container">
    <h1 class="cart-header">Koszyk zakupowy</h1>
    
    @if($cartItems->isEmpty())
        <div class="cart-empty">
            <h2>Twój koszyk jest pusty</h2>
            <p>Dodaj produkty do koszyka, aby kontynuować zakupy</p>
            <a href="{{ route('home') }}" class="btn-checkout">Przejdź do sklepu</a>
        </div>
    @else
        <div class="cart-content">
            <!-- Cart Items -->
            <div class="cart-items">
                @foreach($cartItems as $item)
                    <div class="cart-item">
                        <div>
                            @if($item->product->image_url)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="cart-item-image">
                            @else
                                <img src="https://via.placeholder.com/120x120/1A1A1A/FF4500?text={{ urlencode(substr($item->product->name, 0, 10)) }}" alt="{{ $item->product->name }}" class="cart-item-image">
                            @endif
                        </div>
                        
                        <div class="cart-item-details">
                            <div class="category">{{ $item->product->category->name ?? 'Brak kategorii' }}</div>
                            <h3>{{ $item->product->name }}</h3>
                            <div class="price">{{ number_format($item->product->price, 2) }} PLN</div>
                            <div style="color: #999; font-size: 0.9rem;">SKU: {{ $item->product->sku }}</div>
                        </div>
                        
                        <div class="cart-item-actions">
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="quantity-controls">
                                @csrf
                                @method('PATCH')
                                <button type="button" onclick="this.nextElementSibling.stepDown(); this.form.submit();">-</button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" readonly>
                                <button type="button" onclick="this.previousElementSibling.stepUp(); this.form.submit();">+</button>
                            </form>
                            
                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-remove">Usuń</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Cart Summary -->
            <div class="cart-summary">
                <h2>Podsumowanie</h2>
                
                <div class="summary-row">
                    <span>Produkty ({{ $cartItems->sum('quantity') }} szt.)</span>
                    <span>{{ number_format($total, 2) }} PLN</span>
                </div>
                
                <div class="summary-row">
                    <span>Dostawa</span>
                    <span>Gratis</span>
                </div>
                
                <div class="summary-row total">
                    <span>Razem</span>
                    <span>{{ number_format($total, 2) }} PLN</span>
                </div>
                
                <a href="{{ route('checkout') }}" class="btn-checkout">Przejdź do kasy</a>
                <a href="{{ route('home') }}" class="btn-continue">Kontynuuj zakupy</a>
                
                <form action="{{ route('cart.clear') }}" method="POST" style="margin-top: 1rem;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-continue" style="background-color: transparent; color: #dc3545; border-color: #dc3545;" onclick="return confirm('Czy na pewno chcesz wyczyścić koszyk?')">Wyczyść koszyk</button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
