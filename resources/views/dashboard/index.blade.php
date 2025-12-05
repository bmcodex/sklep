@extends('layouts.app')

@section('title', 'Panel Klienta - BMCODEX')

@section('styles')
<style>
    .dashboard-container {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .dashboard-sidebar {
        background-color: var(--light-gray);
        padding: 1.5rem;
        border-radius: 8px;
        height: fit-content;
        position: sticky;
        top: 100px;
    }
    
    .dashboard-sidebar h3 {
        margin-bottom: 1rem;
        color: var(--primary-color);
    }
    
    .sidebar-menu {
        list-style: none;
    }
    
    .sidebar-menu li {
        margin-bottom: 0.5rem;
    }
    
    .sidebar-menu a {
        display: block;
        padding: 0.8rem;
        color: var(--text-light);
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    
    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        background-color: var(--dark-bg);
        color: var(--primary-color);
    }
    
    .dashboard-content {
        background-color: var(--light-gray);
        padding: 2rem;
        border-radius: 8px;
    }
    
    .dashboard-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--dark-bg);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background-color: var(--dark-bg);
        padding: 1.5rem;
        border-radius: 8px;
        border: 2px solid var(--primary-color);
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: var(--text-gray);
        font-size: 0.9rem;
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    
    .orders-table th,
    .orders-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--dark-bg);
    }
    
    .orders-table th {
        background-color: var(--dark-bg);
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 4px;
        font-size: 0.85rem;
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
    
    @media (max-width: 768px) {
        .dashboard-container {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <aside class="dashboard-sidebar">
        <h3>Menu</h3>
        <ul class="sidebar-menu">
            <li><a href="{{ route('dashboard') }}" class="active">📊 Panel główny</a></li>
            <li><a href="{{ route('orders.index') }}">📦 Moje zamówienia</a></li>
            <li><a href="{{ route('favorites.index') }}">❤️ Ulubione</a></li>
            <li><a href="{{ route('profile') }}">👤 Moje dane</a></li>
        </ul>
    </aside>
    
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h1>Witaj, {{ auth()->user()->first_name }}! 👋</h1>
            <p style="color: var(--text-gray);">Zarządzaj swoim kontem i zamówieniami</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $ordersCount }}</div>
                <div class="stat-label">Zamówienia</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-value">{{ $favoritesCount }}</div>
                <div class="stat-label">Ulubione produkty</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-value">{{ number_format($totalSpent, 2) }} PLN</div>
                <div class="stat-label">Łączna wartość zakupów</div>
            </div>
        </div>
        
        <h2 style="margin-bottom: 1rem;">Ostatnie zamówienia</h2>
        
        @if($recentOrders && $recentOrders->count() > 0)
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Numer zamówienia</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Wartość</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($order->total_price, 2) }} PLN</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem;">
                                    Szczegóły
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <a href="{{ route('orders.index') }}" class="btn btn-primary" style="margin-top: 1rem;">
                Zobacz wszystkie zamówienia
            </a>
        @else
            <p style="color: var(--text-gray); text-align: center; padding: 2rem;">
                Nie masz jeszcze żadnych zamówień.
                <a href="{{ route('products.index') }}" style="color: var(--primary-color);">Zacznij zakupy!</a>
            </p>
        @endif
    </div>
</div>
@endsection
