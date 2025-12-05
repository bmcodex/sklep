@extends('layouts.app')

@section('title', 'BMCODEX - Sklep z częściami do tuningu')

@section('styles')
<style>
    .hero {
        background: linear-gradient(135deg, #1A1A1A 0%, #2A2A2A 100%);
        padding: 4rem 2rem;
        text-align: center;
        border-radius: 8px;
        margin-bottom: 3rem;
        border: 2px solid var(--primary-color);
    }
    
    .hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--text-light);
    }
    
    .hero .tagline {
        font-size: 1.5rem;
        color: var(--primary-color);
        font-weight: 600;
        letter-spacing: 2px;
    }
    
    .filters {
        background-color: var(--light-gray);
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filters select,
    .filters input {
        padding: 0.6rem;
        border: 1px solid var(--primary-color);
        background-color: var(--dark-bg);
        color: var(--text-light);
        border-radius: 4px;
        font-size: 1rem;
    }
    
    .filters button {
        padding: 0.6rem 1.5rem;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .product-card {
        background-color: var(--light-gray);
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 2px solid transparent;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-color);
        box-shadow: 0 8px 16px rgba(255, 69, 0, 0.3);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
        background-color: var(--dark-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }
    
    .product-info {
        padding: 1.5rem;
    }
    
    .product-category {
        color: var(--primary-color);
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    
    .product-name {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        color: var(--text-light);
    }
    
    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .product-stock {
        font-size: 0.9rem;
        color: var(--text-gray);
        margin-bottom: 1rem;
    }
    
    .product-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .product-actions .btn {
        flex: 1;
        text-align: center;
    }
    
    .no-products {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-gray);
        font-size: 1.2rem;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .hero {
            padding: 2rem 1rem;
        }
        
        .hero h1 {
            font-size: 2rem;
        }
        
        .hero .tagline {
            font-size: 1.2rem;
        }
        
        .filters {
            flex-direction: column;
            gap: 0.8rem;
        }
        
        .filters select,
        .filters input,
        .filters button {
            width: 100%;
        }
        
        .products-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .product-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .hero h1 {
            font-size: 1.5rem;
        }
        
        .hero .tagline {
            font-size: 1rem;
        }
        
        .product-image {
            height: 150px;
        }
    }
</style>
@endsection

@section('content')
<div class="hero">
    <h1>BMCODEX</h1>
    <p class="tagline">Performance Without Limits</p>
    <p style="margin-top: 1rem; color: var(--text-gray);">
        Profesjonalne części do tuningu samochodów - Najwyższa jakość, Najlepsza cena
    </p>
</div>

<div class="filters">
    <form method="GET" action="{{ route('products.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; width: 100%;">
        <select name="category" id="category">
            <option value="">Wszystkie kategorie</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        
        <input type="number" name="min_price" placeholder="Cena od" value="{{ request('min_price') }}" step="0.01">
        <input type="number" name="max_price" placeholder="Cena do" value="{{ request('max_price') }}" step="0.01">
        
        <input type="text" name="search" placeholder="Szukaj produktu..." value="{{ request('search') }}">
        
        <button type="submit" class="btn btn-primary">Filtruj</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Wyczyść</a>
    </form>
</div>

@if($products->count() > 0)
    <div class="products-grid">
        @foreach($products as $product)
            <div class="product-card">
                <div class="product-image">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        🏎️
                    @endif
                </div>
                <div class="product-info">
                    <div class="product-category">{{ $product->category->name }}</div>
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <div class="product-price">{{ number_format($product->price, 2) }} PLN</div>
                    <div class="product-stock">
                        @if($product->stock > 10)
                            ✅ Dostępny ({{ $product->stock }} szt.)
                        @elseif($product->stock > 0)
                            ⚠️ Ostatnie sztuki ({{ $product->stock }} szt.)
                        @else
                            ❌ Brak w magazynie
                        @endif
                    </div>
                    <div class="product-actions">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Szczegóły</a>
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    🛒 Dodaj
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div style="margin-top: 2rem;">
        {{ $products->links() }}
    </div>
@else
    <div class="no-products">
        <p>😔 Nie znaleziono produktów spełniających kryteria.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top: 1rem;">
            Pokaż wszystkie produkty
        </a>
    </div>
@endif
@endsection
