@extends('layouts.app')

@section('title', 'Szczegóły zamówienia #' . $order->id . ' - BMCODEX')

@section('styles')
<style>
    .order-detail-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 2rem;
    }
    
    .order-card {
        background-color: var(--light-gray);
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        border: 2px solid var(--primary-color);
    }
    
    .order-card h2 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 0.5rem;
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .order-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .order-status {
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
    }
    
    .status-pending {
        background-color: #ffc107;
        color: #000;
    }
    
    .status-processing {
        background-color: #17a2b8;
        color: #fff;
    }
    
    .status-shipped {
        background-color: #007bff;
        color: #fff;
    }
    
    .status-delivered {
        background-color: #28a745;
        color: #fff;
    }
    
    .status-cancelled {
        background-color: #dc3545;
        color: #fff;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .info-label {
        color: var(--text-gray);
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .info-value {
        color: var(--text-light);
        font-size: 1.1rem;
    }
    
    .product-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background-color: var(--dark-bg);
        border-radius: 8px;
        margin-bottom: 1rem;
        gap: 1rem;
    }
    
    .product-info {
        flex: 1;
    }
    
    .product-name {
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 0.3rem;
    }
    
    .product-quantity {
        color: var(--text-gray);
        font-size: 0.9rem;
    }
    
    .product-price {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.1rem;
        text-align: right;
    }
    
    .total-section {
        border-top: 2px solid var(--primary-color);
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    @media (max-width: 768px) {
        .order-detail-container {
            padding: 1rem;
        }
        
        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .product-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .product-price {
            text-align: left;
        }
    }
</style>
@endsection

@section('content')
<div class="order-detail-container">
    <div class="order-header">
        <h1 class="order-number">Zamówienie #{{ $order->id }}</h1>
        <span class="order-status status-{{ $order->status }}">
            @switch($order->status)
                @case('pending') Oczekujące @break
                @case('processing') W realizacji @break
                @case('shipped') Wysłane @break
                @case('delivered') Dostarczone @break
                @case('cancelled') Anulowane @break
                @default {{ $order->status }}
            @endswitch
        </span>
    </div>
    
    <div class="order-card">
        <h2>Informacje o zamówieniu</h2>
        
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Data zamówienia</span>
                <span class="info-value">{{ $order->created_at->format('d.m.Y H:i') }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Status</span>
                <span class="info-value">
                    @switch($order->status)
                        @case('pending') Oczekujące @break
                        @case('processing') W realizacji @break
                        @case('shipped') Wysłane @break
                        @case('delivered') Dostarczone @break
                        @case('cancelled') Anulowane @break
                        @default {{ $order->status }}
                    @endswitch
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Wartość zamówienia</span>
                <span class="info-value">{{ number_format($order->total_price, 2, ',', ' ') }} PLN</span>
            </div>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Adres dostawy</span>
                <span class="info-value">{{ $order->shipping_address }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Adres rozliczeniowy</span>
                <span class="info-value">{{ $order->billing_address }}</span>
            </div>
        </div>
    </div>
    
    <div class="order-card">
        <h2>Produkty</h2>
        
        @foreach($order->orderItems as $item)
            <div class="product-item">
                <div class="product-info">
                    <div class="product-name">{{ $item->product->name }}</div>
                    <div class="product-quantity">Ilość: {{ $item->quantity }} szt.</div>
                    <div class="product-quantity">Cena jednostkowa: {{ number_format($item->price_per_item, 2, ',', ' ') }} PLN</div>
                </div>
                <div class="product-price">
                    {{ number_format($item->quantity * $item->price_per_item, 2, ',', ' ') }} PLN
                </div>
            </div>
        @endforeach
        
        <div class="total-section">
            <div class="total-row">
                <span>Suma całkowita:</span>
                <span>{{ number_format($order->total_price, 2, ',', ' ') }} PLN</span>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 2rem;">
        <a href="{{ route('account') }}" class="btn btn-secondary">Powrót do konta</a>
    </div>
</div>
@endsection
