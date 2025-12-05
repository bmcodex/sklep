@extends('layouts.app')

@section('title', 'Moje konto - BMCODEX')

@section('styles')
<style>
    .account-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 2rem;
    }
    
    .tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid var(--primary-color);
    }
    
    .tab {
        padding: 1rem 2rem;
        background: none;
        border: none;
        color: var(--text-gray);
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
    }
    
    .tab.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .account-card {
        background-color: var(--light-gray);
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        border: 2px solid var(--primary-color);
    }
    
    .account-card h2 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        color: var(--text-gray);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .form-group input {
        width: 100%;
        padding: 0.8rem;
        background-color: var(--dark-bg);
        border: 1px solid var(--primary-color);
        border-radius: 4px;
        color: var(--text-light);
        font-size: 1rem;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(255, 69, 0, 0.2);
    }
    
    .order-card {
        background-color: var(--dark-bg);
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border-left: 4px solid var(--primary-color);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .order-number {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.1rem;
    }
    
    .order-status {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
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
    
    .order-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        color: var(--text-gray);
    }
    
    .order-detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }
    
    .order-detail-label {
        font-size: 0.85rem;
        color: var(--text-gray);
    }
    
    .order-detail-value {
        font-size: 1rem;
        color: var(--text-light);
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .account-container {
            padding: 1rem;
        }
        
        .tabs {
            flex-direction: column;
            gap: 0;
        }
        
        .tab {
            padding: 0.8rem 1rem;
            text-align: left;
        }
        
        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="account-container">
    <h1>👤 Moje konto</h1>
    
    <div class="tabs">
        <button class="tab active" onclick="switchTab('profile')">Profil</button>
        <button class="tab" onclick="switchTab('orders')">Historia zamówień</button>
    </div>
    
    <!-- Profile Tab -->
    <div id="profile" class="tab-content active">
        <div class="account-card">
            <h2>Edytuj profil</h2>
            
            <form method="POST" action="{{ route('account.update') }}">
                @csrf
                @method('PATCH')
                
                <div class="form-group">
                    <label for="first_name">Imię</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}">
                </div>
                
                <div class="form-group">
                    <label for="last_name">Nazwisko</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Telefon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                </div>
                
                <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            </form>
        </div>
        
        @if(auth()->user()->role === 'admin')
            <div class="account-card">
                <h2>Panel administracyjny</h2>
                <p style="margin-bottom: 1rem; color: var(--text-gray);">
                    Masz uprawnienia administratora. Możesz zarządzać sklepem.
                </p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    Przejdź do panelu admina
                </a>
            </div>
        @endif
        
        <div class="account-card">
            <h2>Wyloguj się</h2>
            <p style="margin-bottom: 1rem; color: var(--text-gray);">
                Zakończ sesję i wyloguj się z konta.
            </p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-secondary">Wyloguj się</button>
            </form>
        </div>
    </div>
    
    <!-- Orders Tab -->
    <div id="orders" class="tab-content">
        <div class="account-card">
            <h2>Moje zamówienia</h2>
            
            @if($orders->count() > 0)
                @foreach($orders as $order)
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-number">Zamówienie #{{ $order->id }}</span>
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
                        
                        <div class="order-details">
                            <div class="order-detail-item">
                                <span class="order-detail-label">Data zamówienia</span>
                                <span class="order-detail-value">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            
                            <div class="order-detail-item">
                                <span class="order-detail-label">Wartość</span>
                                <span class="order-detail-value">{{ number_format($order->total_price, 2, ',', ' ') }} PLN</span>
                            </div>
                            
                            <div class="order-detail-item">
                                <span class="order-detail-label">Liczba produktów</span>
                                <span class="order-detail-value">{{ $order->items->sum('quantity') }}</span>
                            </div>
                        </div>
                        
                        <div style="margin-top: 1rem;">
                            <a href="{{ route('order.details', $order->id) }}" class="btn btn-secondary">Zobacz szczegóły</a>
                        </div>
                    </div>
                @endforeach
                
                <div style="margin-top: 2rem;">
                    {{ $orders->links() }}
                </div>
            @else
                <p style="color: var(--text-gray); text-align: center; padding: 2rem;">
                    Nie masz jeszcze żadnych zamówień.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all tab buttons
        document.querySelectorAll('.tab').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById(tabName).classList.add('active');
        
        // Add active class to clicked button
        event.target.classList.add('active');
    }
</script>
@endsection
