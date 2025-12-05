@extends('layouts.app')

@section('title', $product->name . ' - BMCODEX')

@section('styles')
<style>
    .product-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-top: 2rem;
    }
    
    .product-image-large {
        background-color: var(--light-gray);
        border-radius: 8px;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8rem;
        border: 2px solid var(--primary-color);
    }
    
    .product-details-info {
        background-color: var(--light-gray);
        padding: 2rem;
        border-radius: 8px;
    }
    
    .product-category-badge {
        background-color: var(--primary-color);
        color: var(--text-light);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 1rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .product-title {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    
    .product-price-large {
        font-size: 2.5rem;
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .product-description {
        color: var(--text-gray);
        line-height: 1.8;
        margin-bottom: 2rem;
    }
    
    .product-meta {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .meta-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--dark-bg);
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .quantity-selector input {
        width: 80px;
        padding: 0.5rem;
        text-align: center;
        background-color: var(--dark-bg);
        border: 1px solid var(--primary-color);
        color: var(--text-light);
        border-radius: 4px;
    }
    
    @media (max-width: 768px) {
        .product-detail {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('products.index') }}" class="btn btn-secondary">← Powrót do produktów</a>
</div>

<div class="product-detail">
    <div class="product-image-large">
        🏎️
    </div>
    
    <div class="product-details-info">
        <span class="product-category-badge">{{ $product->category->name }}</span>
        <h1 class="product-title">{{ $product->name }}</h1>
        <div class="product-price-large">{{ number_format($product->price, 2) }} PLN</div>
        
        <p class="product-description">
            {{ $product->description }}
        </p>
        
        <div class="product-meta">
            <div class="meta-item">
                <span>SKU:</span>
                <strong>{{ $product->sku }}</strong>
            </div>
            <div class="meta-item">
                <span>Dostępność:</span>
                <strong>
                    @if($product->stock > 10)
                        <span style="color: #28a745;">✅ Dostępny ({{ $product->stock }} szt.)</span>
                    @elseif($product->stock > 0)
                        <span style="color: #ffc107;">⚠️ Ostatnie sztuki ({{ $product->stock }} szt.)</span>
                    @else
                        <span style="color: #dc3545;">❌ Brak w magazynie</span>
                    @endif
                </strong>
            </div>
            <div class="meta-item">
                <span>Kategoria:</span>
                <strong>{{ $product->category->name }}</strong>
            </div>
        </div>
        
        @if($product->stock > 0)
            <form action="{{ route('cart.add', $product) }}" method="POST">
                @csrf
                <div class="quantity-selector">
                    <label for="quantity">Ilość:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.2rem;">
                    🛒 Dodaj do koszyka
                </button>
            </form>
        @else
            <button class="btn btn-secondary" disabled style="width: 100%; padding: 1rem;">
                Produkt niedostępny
            </button>
        @endif
        
        @auth
            <form action="{{ route('favorites.add', $product) }}" method="POST" style="margin-top: 1rem;">
                @csrf
                <button type="submit" class="btn btn-secondary" style="width: 100%;">
                    ❤️ Dodaj do ulubionych
                </button>
            </form>
        @endauth
    </div>
</div>
@endsection
