@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <!-- PayU Logo -->
        <div class="text-center mb-8 animate-fade-in">
            <div class="inline-block bg-orange-500 px-8 py-4 rounded-lg mb-4">
                <h1 class="text-4xl font-bold text-white">PayU</h1>
            </div>
            <p class="text-gray-400 text-lg">Tryb testowy - symulacja płatności</p>
        </div>

        <!-- Order Summary -->
        <div class="bg-gray-800 rounded-lg p-6 mb-6 shadow-xl border border-gray-700 animate-slide-up">
            <h2 class="text-xl font-bold text-white mb-4">Podsumowanie zamówienia</h2>
            
            <div class="space-y-2 text-gray-300">
                <div class="flex justify-between">
                    <span>Numer zamówienia:</span>
                    <span class="font-bold text-white">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Data:</span>
                    <span>{{ $order->created_at->format('d.m.Y H:i') }}</span>
                </div>
                <div class="flex justify-between text-xl font-bold border-t border-gray-700 pt-2 mt-2">
                    <span class="text-white">Do zapłaty:</span>
                    <span class="text-orange-500">{{ number_format($order->total_price, 2) }} PLN</span>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <form action="{{ route('payu.process', $order) }}" method="POST" class="bg-gray-800 rounded-lg p-6 shadow-xl border border-gray-700 animate-slide-up" style="animation-delay: 0.1s;">
            @csrf
            
            <h3 class="text-lg font-bold text-white mb-4">Wybierz metodę płatności</h3>
            
            <div class="space-y-3">
                <!-- Blik -->
                <label class="flex items-center p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition">
                    <input type="radio" name="payment_method" value="blik" class="mr-3" required>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white rounded flex items-center justify-center mr-3">
                            <span class="text-xl font-bold text-gray-800">BLIK</span>
                        </div>
                        <span class="text-white font-medium">BLIK</span>
                    </div>
                </label>

                <!-- Karta płatnicza -->
                <label class="flex items-center p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition">
                    <input type="radio" name="payment_method" value="card" class="mr-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white rounded flex items-center justify-center mr-3">
                            <span class="text-2xl">💳</span>
                        </div>
                        <span class="text-white font-medium">Karta płatnicza</span>
                    </div>
                </label>

                <!-- Przelew bankowy -->
                <label class="flex items-center p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition">
                    <input type="radio" name="payment_method" value="transfer" class="mr-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white rounded flex items-center justify-center mr-3">
                            <span class="text-2xl">🏦</span>
                        </div>
                        <span class="text-white font-medium">Przelew bankowy</span>
                    </div>
                </label>

                <!-- PayPo -->
                <label class="flex items-center p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition">
                    <input type="radio" name="payment_method" value="paypo" class="mr-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-500 rounded flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-sm">PayPo</span>
                        </div>
                        <span class="text-white font-medium">PayPo - kup teraz, zapłać za 30 dni</span>
                    </div>
                </label>
            </div>

            <!-- Sandbox Info -->
            <div class="mt-6 p-4 bg-yellow-900 bg-opacity-30 border border-yellow-600 rounded-lg">
                <p class="text-yellow-400 text-sm">
                    ⚠️ <strong>Tryb testowy (Sandbox)</strong><br>
                    To jest symulacja płatności. Żadne prawdziwe środki nie zostaną pobrane. 
                    Po kliknięciu "Zapłać" płatność zostanie automatycznie zatwierdzona.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-6">
                <a href="{{ route('payu.cancel', $order) }}" 
                   class="flex-1 bg-gray-700 text-white px-6 py-4 rounded-lg hover:bg-gray-600 transition text-center font-medium">
                    ← Anuluj
                </a>
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-4 rounded-lg hover:from-orange-600 hover:to-orange-700 transition font-bold text-lg shadow-lg transform hover:scale-105">
                    🔒 Zapłać {{ number_format($order->total_price, 2) }} PLN
                </button>
            </div>
        </form>

        <!-- Security Info -->
        <div class="mt-6 text-center text-gray-500 text-sm">
            <p>🔒 Płatność zabezpieczona przez PayU Sandbox</p>
        </div>
    </div>
</div>
@endsection
