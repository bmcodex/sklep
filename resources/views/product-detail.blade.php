@extends('layouts.app')

@section('title', $product->name . ' - BMCODEX')

@section('styles')
<style>
    .product-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin: 3rem 0;
    }
    
    .product-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid var(--primary-color);
    }
    
    .product-info h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--text-light);
    }
    
    .product-category {
        display: inline-block;
        background-color: var(--primary-color);
        color: var(--dark-bg);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    
    .product-price {
        font-size: 2.5rem;
        color: var(--primary-color);
        font-weight: 700;
        margin: 1.5rem 0;
    }
    
    .product-description {
        line-height: 1.8;
        margin: 1.5rem 0;
        color: var(--text-light);
    }
    
    .product-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin: 2rem 0;
        padding: 1.5rem;
        background-color: var(--light-gray);
        border-radius: 8px;
    }
    
    .meta-item {
        display: flex;
        flex-direction: column;
    }
    
    .meta-label {
        font-size: 0.9rem;
        color: #999;
        margin-bottom: 0.3rem;
    }
    
    .meta-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-light);
    }
    
    .stock-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 600;
    }
    
    .stock-status.in-stock {
        background-color: #28a745;
        color: white;
    }
    
    .stock-status.low-stock {
        background-color: #ffc107;
        color: #000;
    }
    
    .stock-status.out-of-stock {
        background-color: #dc3545;
        color: white;
    }
    
    .add-to-cart-form {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .quantity-input {
        width: 100px;
        padding: 0.8rem;
        border: 2px solid var(--primary-color);
        background-color: var(--dark-bg);
        color: var(--text-light);
        border-radius: 4px;
        font-size: 1.1rem;
        text-align: center;
    }
    
    .btn-add-cart {
        flex: 1;
        padding: 1rem 2rem;
        background-color: var(--primary-color);
        color: var(--dark-bg);
        border: none;
        border-radius: 4px;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-add-cart:hover {
        background-color: #ff6a33;
        transform: translateY(-2px);
    }
    
    .btn-add-cart:disabled {
        background-color: #666;
        cursor: not-allowed;
        transform: none;
    }
    
    .related-products {
        margin-top: 4rem;
    }
    
    .related-products h2 {
        font-size: 2rem;
        margin-bottom: 2rem;
        color: var(--text-light);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .product-detail {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .product-info h1 {
            font-size: 2rem;
        }
        
        .product-price {
            font-size: 2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav style="margin: 2rem 0; color: #999;">
        <a href="{{ route('home') }}" style="color: var(--primary-color);">Strona główna</a> / 
        @if($product->category)
            <a href="{{ route('products.category', $product->category) }}" style="color: var(--primary-color);">{{ $product->category->name }}</a> / 
        @endif
        <span>{{ $product->name }}</span>
    </nav>

    <div class="product-detail">
        <!-- Product Image -->
        <div>
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
            @else
                <img src="https://via.placeholder.com/500x500/1A1A1A/FF4500?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="product-image">
            @endif
        </div>

        <!-- Product Info -->
        <div class="product-info">
            @if($product->category)
                <span class="product-category">{{ $product->category->name }}</span>
            @endif
            
            <h1>{{ $product->name }}</h1>
            
            <div class="product-price">{{ number_format($product->price, 2) }} PLN</div>
            
            <!-- Stock Status -->
            @if($product->stock > 10)
                <span class="stock-status in-stock">✓ Dostępny ({{ $product->stock }} szt.)</span>
            @elseif($product->stock > 0)
                <span class="stock-status low-stock">⚠ Ostatnie sztuki ({{ $product->stock }} szt.)</span>
            @else
                <span class="stock-status out-of-stock">✗ Brak w magazynie</span>
            @endif
            
            <div class="product-description">
                {{ $product->description ?? 'Brak opisu produktu.' }}
            </div>
            
            <!-- Product Meta -->
            <div class="product-meta">
                <div class="meta-item">
                    <span class="meta-label">SKU</span>
                    <span class="meta-value">{{ $product->sku }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Kategoria</span>
                    <span class="meta-value">{{ $product->category->name ?? 'Brak' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Dostępność</span>
                    <span class="meta-value">{{ $product->stock }} szt.</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Cena</span>
                    <span class="meta-value">{{ number_format($product->price, 2) }} PLN</span>
                </div>
            </div>
            
            <!-- Add to Cart Form -->
            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="quantity-input" required>
                    <button type="submit" class="btn-add-cart">Dodaj do koszyka</button>
                </form>
            @else
                <button class="btn-add-cart" disabled>Produkt niedostępny</button>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="related-products">
            <h2>Podobne produkty</h2>
            <div class="products-grid">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="product-card">
                        <div class="product-image-wrapper">
                            @if($relatedProduct->image_url)
                                <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}" class="product-img">
                            @else
                                <img src="https://via.placeholder.com/300x300/1A1A1A/FF4500?text={{ urlencode($relatedProduct->name) }}" alt="{{ $relatedProduct->name }}" class="product-img">
                            @endif
                            @if($relatedProduct->stock <= 5 && $relatedProduct->stock > 0)
                                <span class="badge badge-warning">Ostatnie sztuki!</span>
                            @elseif($relatedProduct->stock == 0)
                                <span class="badge badge-danger">Brak w magazynie</span>
                            @endif
                        </div>
                        <div class="product-details">
                            <span class="product-category-tag">{{ $relatedProduct->category->name ?? 'Brak kategorii' }}</span>
                            <h3 class="product-name">{{ $relatedProduct->name }}</h3>
                            <p class="product-desc">{{ Str::limit($relatedProduct->description, 100) }}</p>
                            <div class="product-footer">
                                <span class="product-price">{{ number_format($relatedProduct->price, 2) }} PLN</span>
                                <a href="{{ route('products.show', $relatedProduct) }}" class="btn-details">Szczegóły</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
