@extends('layouts.app')

@section('title', 'Ulubione produkty - BMCODEX')

@section('styles')
<style>
    .favorites-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
    }
    
    .favorites-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .favorite-card {
        background-color: var(--light-gray);
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid var(--primary-color);
        transition: transform 0.3s;
        position: relative;
    }
    
    .favorite-card:hover {
        transform: translateY(-5px);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background-color: var(--dark-bg);
    }
    
    .product-info {
        padding: 1.5rem;
    }
    
    .product-category {
        color: var(--primary-color);
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    
    .product-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }
    
    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 1rem 0;
    }
    
    .product-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .btn-remove {
        flex: 1;
        background-color: #dc3545;
        color: white;
    }
    
    .btn-remove:hover {
        background-color: #c82333;
    }
    
    .btn-view {
        flex: 1;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-gray);
    }
    
    .empty-state h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: var(--text-light);
    }
    
    .empty-state p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 768px) {
        .favorites-container {
            padding: 1rem;
        }
        
        .favorites-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="favorites-container">
    <h1>❤️ Moje ulubione produkty</h1>
    
    @if($favorites->count() > 0)
        <div class="favorites-grid">
            @foreach($favorites as $favorite)
                <div class="favorite-card">
                    <img src="{{ $favorite->product->image_url ?? 'https://via.placeholder.com/300x200?text=Brak+zdjęcia' }}" 
                         alt="{{ $favorite->product->name }}" 
                         class="product-image">
                    
                    <div class="product-info">
                        <div class="product-category">
                            {{ $favorite->product->category->name ?? 'Bez kategorii' }}
                        </div>
                        
                        <h3 class="product-name">{{ $favorite->product->name }}</h3>
                        
                        <div class="product-price">
                            {{ number_format($favorite->product->price, 2, ',', ' ') }} PLN
                        </div>
                        
                        @if($favorite->product->stock > 0)
                            <span style="color: #28a745; font-size: 0.9rem;">
                                ✓ Dostępny ({{ $favorite->product->stock }} szt.)
                            </span>
                        @else
                            <span style="color: #dc3545; font-size: 0.9rem;">
                                ⚠ Brak w magazynie
                            </span>
                        @endif
                        
                        <div class="product-actions">
                            <a href="{{ route('products.show', $favorite->product) }}" class="btn btn-primary btn-view">
                                Zobacz
                            </a>
                            
                            <form action="{{ route('favorites.remove', $favorite->product) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-remove" style="width: 100%;">
                                    Usuń
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <h2>Brak ulubionych produktów</h2>
            <p>Nie dodałeś jeszcze żadnych produktów do ulubionych.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                Przeglądaj produkty
            </a>
        </div>
    @endif
</div>
@endsection
