@extends('layouts.app')

@section('content')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    .animate-slide-up {
        animation: slideUp 0.6s ease-out;
    }
    .payment-method:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 20px rgba(249, 115, 22, 0.3);
    }
    .payment-method input:checked + div {
        border-color: #f97316;
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.1) 0%, rgba(249, 115, 22, 0.05) 100%);
    }
</style>

<div class="min-h-screen bg-gradient-to-b from-gray-900 to-black py-12 px-4">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in">
            <div class="inline-flex items-center bg-gradient-to-r from-orange-500 to-orange-600 px-10 py-5 rounded-2xl shadow-2xl mb-4">
                <svg class="w-8 h-8 text-white mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                </svg>
                <h1 class="text-4xl font-bold text-white">PayU</h1>
            </div>
            <div class="inline-block bg-yellow-500 bg-opacity-20 border border-yellow-500 rounded-full px-6 py-2">
                <p class="text-yellow-400 font-semibold">🧪 Tryb testowy - Sandbox</p>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            
            <!-- Left Column - Order Summary -->
            <div class="md:col-span-1 animate-slide-up">
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 shadow-2xl border border-gray-700">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Zamówienie
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-700">
                            <span class="text-gray-400">Numer</span>
                            <span class="font-mono text-orange-500 font-bold">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-700">
                            <span class="text-gray-400">Data</span>
                            <span class="text-white">{{ $order->created_at->format('d.m.Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-700">
                            <span class="text-gray-400">Godzina</span>
                            <span class="text-white">{{ $order->created_at->format('H:i') }}</span>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t-2 border-orange-500">
                            <div class="flex justify-between items-center">
                                <span class="text-lg text-white font-semibold">Do zapłaty</span>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-orange-500">
                                        {{ number_format($order->total_price, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-400">PLN</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Badge -->
                <div class="mt-6 bg-gray-800 bg-opacity-50 rounded-xl p-4 border border-gray-700">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        <div>
                            <p class="text-white font-semibold text-sm">Bezpieczna płatność</p>
                            <p class="text-gray-400 text-xs mt-1">Szyfrowanie SSL 256-bit</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Payment Methods -->
            <div class="md:col-span-2 animate-slide-up" style="animation-delay: 0.1s;">
                <form action="{{ route('payu.process', $order) }}" method="POST" class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 shadow-2xl border border-gray-700">
                    @csrf
                    
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Wybierz metodę płatności
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- BLIK -->
                        <label class="payment-method flex items-center p-5 bg-gray-700 bg-opacity-50 rounded-xl cursor-pointer hover:bg-gray-700 transition-all duration-300 border-2 border-transparent">
                            <input type="radio" name="payment_method" value="blik" class="hidden" required>
                            <div class="flex items-center w-full">
                                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                    <span class="text-2xl font-black text-blue-600">BLIK</span>
                                </div>
                                <div class="flex-1">
                                    <span class="text-white font-bold text-lg block">BLIK</span>
                                    <span class="text-gray-400 text-sm">Kod z aplikacji bankowej</span>
                                </div>
                                <div class="text-orange-500 opacity-0 transition-opacity">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                </div>
                            </div>
                        </label>

                        <!-- Karta płatnicza -->
                        <label class="payment-method flex items-center p-5 bg-gray-700 bg-opacity-50 rounded-xl cursor-pointer hover:bg-gray-700 transition-all duration-300 border-2 border-transparent">
                            <input type="radio" name="payment_method" value="card" class="hidden">
                            <div class="flex items-center w-full">
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="text-white font-bold text-lg block">Karta płatnicza</span>
                                    <span class="text-gray-400 text-sm">Visa, Mastercard, Maestro</span>
                                </div>
                                <div class="text-orange-500 opacity-0 transition-opacity">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                </div>
                            </div>
                        </label>

                        <!-- Przelew bankowy -->
                        <label class="payment-method flex items-center p-5 bg-gray-700 bg-opacity-50 rounded-xl cursor-pointer hover:bg-gray-700 transition-all duration-300 border-2 border-transparent">
                            <input type="radio" name="payment_method" value="transfer" class="hidden">
                            <div class="flex items-center w-full">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="text-white font-bold text-lg block">Przelew bankowy</span>
                                    <span class="text-gray-400 text-sm">Szybki przelew online</span>
                                </div>
                                <div class="text-orange-500 opacity-0 transition-opacity">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                </div>
                            </div>
                        </label>

                        <!-- PayPo -->
                        <label class="payment-method flex items-center p-5 bg-gray-700 bg-opacity-50 rounded-xl cursor-pointer hover:bg-gray-700 transition-all duration-300 border-2 border-transparent">
                            <input type="radio" name="payment_method" value="paypo" class="hidden">
                            <div class="flex items-center w-full">
                                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                    <span class="text-white font-black text-lg">PayPo</span>
                                </div>
                                <div class="flex-1">
                                    <span class="text-white font-bold text-lg block">PayPo</span>
                                    <span class="text-gray-400 text-sm">Kup teraz, zapłać za 30 dni</span>
                                </div>
                                <div class="text-orange-500 opacity-0 transition-opacity">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Sandbox Info -->
                    <div class="mt-6 p-5 bg-yellow-900 bg-opacity-20 border-2 border-yellow-600 rounded-xl">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            <div>
                                <p class="text-yellow-400 font-bold text-sm">Tryb testowy (Sandbox)</p>
                                <p class="text-yellow-300 text-xs mt-1">To jest symulacja płatności. Żadne prawdziwe środki nie zostaną pobrane. Po kliknięciu "Zapłać" płatność zostanie automatycznie zatwierdzona.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <a href="{{ route('payu.cancel', $order) }}" 
                           class="flex-1 bg-gray-700 text-white px-8 py-4 rounded-xl hover:bg-gray-600 transition-all duration-300 text-center font-semibold flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Anuluj płatność
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-orange-500 via-orange-600 to-orange-500 text-white px-8 py-4 rounded-xl hover:from-orange-600 hover:via-orange-700 hover:to-orange-600 transition-all duration-300 font-bold text-lg shadow-2xl transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Zapłać {{ number_format($order->total_price, 2) }} PLN
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-gray-500 text-sm animate-fade-in" style="animation-delay: 0.3s;">
            <p class="flex items-center justify-center">
                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                </svg>
                Płatność zabezpieczona przez PayU Sandbox | SSL 256-bit
            </p>
        </div>
    </div>
</div>

<script>
    // Dodaj efekt zaznaczenia dla wybranej metody płatności
    document.querySelectorAll('.payment-method input').forEach(input => {
        input.addEventListener('change', function() {
            document.querySelectorAll('.payment-method').forEach(label => {
                label.querySelector('.text-orange-500').style.opacity = '0';
            });
            if(this.checked) {
                this.closest('.payment-method').querySelector('.text-orange-500').style.opacity = '1';
            }
        });
    });
</script>
@endsection
