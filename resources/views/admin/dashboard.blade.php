@extends('layouts.app')

@section('title', 'Panel Administracyjny - BMCODEX')

@section('styles')
<style>
    .admin-container {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .admin-sidebar {
        background-color: var(--light-gray);
        padding: 1.5rem;
        border-radius: 8px;
        height: fit-content;
        position: sticky;
        top: 100px;
    }
    
    .admin-sidebar h3 {
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
    
    .admin-content {
        background-color: var(--light-gray);
        padding: 2rem;
        border-radius: 8px;
    }
    
    .admin-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--dark-bg);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    
    .stat-card {
        background-color: var(--dark-bg);
        padding: 2rem;
        border-radius: 8px;
        border: 2px solid var(--primary-color);
        text-align: center;
    }
    
    .stat-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: var(--text-gray);
        font-size: 1rem;
        font-weight: 600;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 3rem;
    }
    
    .action-card {
        background-color: var(--dark-bg);
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
        transition: transform 0.3s;
    }
    
    .action-card:hover {
        transform: translateY(-5px);
    }
    
    .action-card h3 {
        margin-bottom: 1rem;
        color: var(--primary-color);
    }
    
    .recent-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    
    .recent-table th,
    .recent-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--dark-bg);
    }
    
    .recent-table th {
        background-color: var(--dark-bg);
        color: var(--primary-color);
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .admin-container {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="admin-container">
    <aside class="admin-sidebar">
        <h3>🔧 Panel Admin</h3>
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.dashboard') }}" class="active">📊 Dashboard</a></li>
            <li><a href="{{ route('admin.products.index') }}">📦 Produkty</a></li>
            <li><a href="{{ route('admin.orders.index') }}">🛒 Zamówienia</a></li>
            <li><a href="{{ route('admin.users.index') }}">👥 Użytkownicy</a></li>
            <li><a href="{{ route('admin.categories.index') }}">📁 Kategorie</a></li>
            <!-- Raporty usunięte -->
        </ul>
    </aside>
    
    <div class="admin-content">
        <div class="admin-header">
            <h1>Panel Administracyjny</h1>
            <p style="color: var(--text-gray);">Zarządzaj sklepem BMCODEX</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-value">{{ $productsCount }}</div>
                <div class="stat-label">Produkty</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🛒</div>
                <div class="stat-value">{{ $ordersCount }}</div>
                <div class="stat-label">Zamówienia</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value">{{ $usersCount }}</div>
                <div class="stat-label">Użytkownicy</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-value">{{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-label">Przychód (PLN)</div>
            </div>
        </div>
        
        <h2 style="margin-bottom: 1rem;">Szybkie akcje</h2>
        <div class="quick-actions">
            <div class="action-card">
                <h3>➕ Dodaj produkt</h3>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="width: 100%;">
                    Nowy produkt
                </a>
            </div>
            
            <div class="action-card">
                <h3>📋 Zamówienia oczekujące</h3>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-primary" style="width: 100%;">
                    Zobacz ({{ $pendingOrdersCount }})
                </a>
            </div>
            
            <div class="action-card">
                <h3>⚠️ Niski stan magazynowy</h3>
                <a href="{{ route('admin.products.low-stock') }}" class="btn btn-primary" style="width: 100%;">
                    Sprawdź ({{ $lowStockCount }})
                </a>
            </div>
            
            <div class="action-card">
                <h3>📊 Raporty sprzedaży</h3>
                <a href="#" class="btn btn-primary" style="width: 100%; opacity: 0.5; cursor: not-allowed;" onclick="return false;">
                    Generuj raport
                </a>
            </div>
        </div>
        
        <h2 style="margin-bottom: 1rem;">Ostatnie zamówienia</h2>
        @if($recentOrders && $recentOrders->count() > 0)
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Klient</th>
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
                            <td>
                                @if($order->user)
                                    {{ $order->user->first_name }} {{ $order->user->last_name }}
                                @else
                                    <span style="color: #999;">Gość ({{ $order->guest_email }})</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($order->total_price, 2) }} PLN</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary" style="padding: 0.4rem 0.8rem;">
                                    Szczegóły
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: var(--text-gray); text-align: center; padding: 2rem;">
                Brak zamówień
            </p>
        @endif
    </div>
</div>
@endsection
