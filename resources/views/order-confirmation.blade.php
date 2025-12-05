@extends('layouts.app')

@section('title', 'Potwierdzenie zamówienia - BMCODEX')

@section('content')
<div class="container" style="max-width: 800px; margin: 4rem auto; text-align: center;">
    <div style="background-color: var(--light-gray); padding: 3rem; border-radius: 8px; border: 2px solid #28a745;">
        <div style="font-size: 4rem; color: #28a745; margin-bottom: 1rem;">✓</div>
        
        <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--text-light);">
            Dziękujemy za zamówienie!
        </h1>
        
        <p style="font-size: 1.2rem; color: #999; margin-bottom: 2rem;">
            Twoje zamówienie zostało przyjęte i jest w trakcie realizacji.
        </p>

        <div style="background-color: var(--dark-bg); padding: 2rem; border-radius: 8px; margin-bottom: 2rem; text-align: left;">
            <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem; color: var(--primary-color);">
                Szczegóły zamówienia #{{ $order->id }}
            </h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <div style="color: #999; font-size: 0.9rem;">Data zamówienia</div>
                    <div style="color: var(--text-light); font-weight: 600;">{{ $order->created_at->format('d.m.Y H:i') }}</div>
                </div>
                <div>
                    <div style="color: #999; font-size: 0.9rem;">Status</div>
                    <div style="color: #ffc107; font-weight: 600;">Oczekujące</div>
                </div>
            </div>

            <div style="border-top: 1px solid #333; padding-top: 1.5rem; margin-top: 1.5rem;">
                <h3 style="color: var(--text-light); margin-bottom: 1rem;">Produkty:</h3>
                @foreach($order->orderItems as $item)
                    <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid #333;">
                        <div style="color: var(--text-light);">
                            {{ $item->product->name }} <span style="color: #999;">({{ $item->quantity }} szt.)</span>
                        </div>
                        <div style="color: var(--primary-color); font-weight: 700;">
                            {{ number_format($item->price_per_item * $item->quantity, 2) }} PLN
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="display: flex; justify-content: space-between; padding: 1.5rem 0; font-size: 1.5rem; font-weight: 700; border-top: 2px solid var(--primary-color); margin-top: 1rem;">
                <span style="color: var(--text-light);">Razem</span>
                <span style="color: var(--primary-color);">{{ number_format($order->total_price, 2) }} PLN</span>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #333;">
                <div style="margin-bottom: 1rem;">
                    <div style="color: #999; font-size: 0.9rem; margin-bottom: 0.3rem;">Adres dostawy:</div>
                    <div style="color: var(--text-light);">{{ $order->shipping_address }}</div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="{{ route('home') }}" 
                style="padding: 1rem 2rem; background-color: var(--primary-color); color: var(--dark-bg); border: none; border-radius: 4px; font-size: 1.1rem; font-weight: 700; text-decoration: none; display: inline-block;">
                Powrót do sklepu
            </a>
        </div>
    </div>
</div>
@endsection
