@extends('layouts.app')

@section('title', 'Zarządzanie zamówieniami - Panel Admin')

@section('content')
<div class="container" style="margin-top: 2rem;">
    <h1 style="margin-bottom: 2rem; color: var(--primary-color);">Zarządzanie zamówieniami</h1>

    @if(session('success'))
        <div style="background-color: #28a745; color: white; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background-color: var(--light-gray); padding: 2rem; border-radius: 8px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--primary-color);">
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">ID</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Klient</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Data</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Status</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Kwota</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #333;">
                        <td style="padding: 1rem; color: var(--text-light);">#{{ $order->id }}</td>
                        <td style="padding: 1rem; color: var(--text-light);">
                            @if($order->user)
                                {{ $order->user->first_name }} {{ $order->user->last_name }}
                            @else
                                <span style="color: #999;">Gość ({{ $order->guest_email }})</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; color: var(--text-light);">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td style="padding: 1rem;">
                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" 
                                    style="padding: 0.5rem; background-color: var(--dark-bg); color: var(--text-light); border: 1px solid var(--primary-color); border-radius: 4px;">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Oczekujące</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>W realizacji</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Wysłane</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Dostarczone</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Anulowane</option>
                                </select>
                            </form>
                        </td>
                        <td style="padding: 1rem; color: var(--primary-color); font-weight: 700;">{{ number_format($order->total_price, 2) }} PLN</td>
                        <td style="padding: 1rem;">
                            <button onclick="toggleDetails({{ $order->id }})" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                                Szczegóły
                            </button>
                        </td>
                    </tr>
                    <tr id="details-{{ $order->id }}" style="display: none; background-color: var(--dark-bg);">
                        <td colspan="6" style="padding: 1.5rem;">
                            <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Szczegóły zamówienia #{{ $order->id }}</h3>
                            
                            <div style="margin-bottom: 1rem;">
                                <strong style="color: var(--text-light);">Adres dostawy:</strong><br>
                                <span style="color: #999;">{{ $order->shipping_address }}</span>
                            </div>

                            <h4 style="color: var(--text-light); margin-bottom: 0.5rem;">Produkty:</h4>
                            <table style="width: 100%; margin-top: 0.5rem;">
                                @foreach($order->items as $item)
                                    <tr style="border-bottom: 1px solid #333;">
                                        <td style="padding: 0.5rem; color: var(--text-light);">{{ $item->product->name }}</td>
                                        <td style="padding: 0.5rem; color: #999;">{{ $item->quantity }} szt.</td>
                                        <td style="padding: 0.5rem; color: var(--primary-color);">{{ number_format($item->price_per_item * $item->quantity, 2) }} PLN</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #999;">
                            Brak zamówień
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script>
function toggleDetails(orderId) {
    const row = document.getElementById('details-' + orderId);
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
}
</script>
@endsection
