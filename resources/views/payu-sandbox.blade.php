@extends('layouts.app')

@section('content')
<style>
    body {
        background: #ffffff;
        font-family: 'Helvetica Neue', Arial, sans-serif;
    }
    .nike-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 20px;
    }
    .nike-header {
        text-align: center;
        margin-bottom: 60px;
    }
    .nike-logo {
        font-size: 48px;
        font-weight: 900;
        letter-spacing: -2px;
        color: #111;
        margin-bottom: 8px;
    }
    .nike-subtitle {
        font-size: 14px;
        color: #757575;
        font-weight: 400;
        letter-spacing: 0.5px;
    }
    .nike-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 80px;
        margin-top: 40px;
    }
    @media (max-width: 768px) {
        .nike-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }
    }
    .nike-summary {
        padding: 40px;
        background: #f5f5f5;
        border-radius: 0;
    }
    .nike-summary-title {
        font-size: 24px;
        font-weight: 700;
        color: #111;
        margin-bottom: 32px;
        letter-spacing: -0.5px;
    }
    .nike-summary-row {
        display: flex;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid #e5e5e5;
    }
    .nike-summary-label {
        font-size: 15px;
        color: #757575;
        font-weight: 400;
    }
    .nike-summary-value {
        font-size: 15px;
        color: #111;
        font-weight: 500;
    }
    .nike-total {
        display: flex;
        justify-content: space-between;
        padding: 24px 0 0 0;
        margin-top: 16px;
    }
    .nike-total-label {
        font-size: 18px;
        color: #111;
        font-weight: 700;
    }
    .nike-total-value {
        font-size: 24px;
        color: #111;
        font-weight: 900;
    }
    .nike-payment {
        padding: 40px 0;
    }
    .nike-section-title {
        font-size: 28px;
        font-weight: 700;
        color: #111;
        margin-bottom: 32px;
        letter-spacing: -0.5px;
    }
    .nike-payment-method {
        border: 2px solid #e5e5e5;
        padding: 24px;
        margin-bottom: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
    }
    .nike-payment-method:hover {
        border-color: #111;
    }
    .nike-payment-method.selected {
        border-color: #111;
        background: #f5f5f5;
    }
    .nike-payment-method input[type="radio"] {
        display: none;
    }
    .nike-payment-content {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .nike-payment-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-center;
        background: #fff;
        border: 1px solid #e5e5e5;
    }
    .nike-payment-info {
        flex: 1;
    }
    .nike-payment-name {
        font-size: 18px;
        font-weight: 700;
        color: #111;
        margin-bottom: 4px;
    }
    .nike-payment-desc {
        font-size: 14px;
        color: #757575;
    }
    .nike-payment-check {
        width: 24px;
        height: 24px;
        border: 2px solid #e5e5e5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .nike-payment-method.selected .nike-payment-check {
        border-color: #111;
        background: #111;
    }
    .nike-payment-check svg {
        width: 14px;
        height: 14px;
        fill: #fff;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .nike-payment-method.selected .nike-payment-check svg {
        opacity: 1;
    }
    .nike-sandbox-notice {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 20px;
        margin: 32px 0;
    }
    .nike-sandbox-title {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        margin-bottom: 8px;
    }
    .nike-sandbox-text {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
    }
    .nike-buttons {
        display: flex;
        gap: 16px;
        margin-top: 40px;
    }
    .nike-btn {
        flex: 1;
        padding: 18px 32px;
        font-size: 16px;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none;
        display: inline-block;
    }
    .nike-btn-secondary {
        background: #fff;
        color: #111;
        border: 2px solid #e5e5e5;
    }
    .nike-btn-secondary:hover {
        border-color: #111;
    }
    .nike-btn-primary {
        background: #111;
        color: #fff;
        border: 2px solid #111;
    }
    .nike-btn-primary:hover {
        background: #000;
    }
    .nike-footer {
        text-align: center;
        margin-top: 60px;
        padding-top: 40px;
        border-top: 1px solid #e5e5e5;
    }
    .nike-footer-text {
        font-size: 14px;
        color: #757575;
    }
</style>

<div class="nike-container">
    <!-- Header -->
    <div class="nike-header">
        <div class="nike-logo">PayU</div>
        <div class="nike-subtitle">BEZPIECZNA PŁATNOŚĆ ONLINE</div>
    </div>

    <!-- Main Grid -->
    <div class="nike-grid">
        <!-- Left Column - Summary -->
        <div>
            <div class="nike-summary">
                <h2 class="nike-summary-title">Podsumowanie</h2>
                
                <div class="nike-summary-row">
                    <span class="nike-summary-label">Zamówienie</span>
                    <span class="nike-summary-value">#{{ $order->id }}</span>
                </div>
                
                <div class="nike-summary-row">
                    <span class="nike-summary-label">Data</span>
                    <span class="nike-summary-value">{{ $order->created_at->format('d.m.Y') }}</span>
                </div>
                
                <div class="nike-summary-row">
                    <span class="nike-summary-label">Godzina</span>
                    <span class="nike-summary-value">{{ $order->created_at->format('H:i') }}</span>
                </div>
                
                <div class="nike-total">
                    <span class="nike-total-label">Razem</span>
                    <span class="nike-total-value">{{ number_format($order->total_price, 2) }} PLN</span>
                </div>
            </div>
        </div>

        <!-- Right Column - Payment Methods -->
        <div class="nike-payment">
            <h2 class="nike-section-title">Wybierz metodę płatności</h2>
            
            <form action="{{ route('payu.process', $order) }}" method="POST" id="paymentForm">
                @csrf
                
                <!-- BLIK -->
                <label class="nike-payment-method" data-method="blik">
                    <input type="radio" name="payment_method" value="blik" required>
                    <div class="nike-payment-content">
                        <div class="nike-payment-icon">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <rect width="40" height="40" fill="#111"/>
                                <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#fff" font-size="12" font-weight="bold">BLIK</text>
                            </svg>
                        </div>
                        <div class="nike-payment-info">
                            <div class="nike-payment-name">BLIK</div>
                            <div class="nike-payment-desc">Kod z aplikacji bankowej</div>
                        </div>
                        <div class="nike-payment-check">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                    </div>
                </label>

                <!-- Karta -->
                <label class="nike-payment-method" data-method="card">
                    <input type="radio" name="payment_method" value="card">
                    <div class="nike-payment-content">
                        <div class="nike-payment-icon">
                            <svg width="40" height="30" viewBox="0 0 40 30" fill="none">
                                <rect width="40" height="30" rx="4" fill="#111"/>
                                <rect x="4" y="8" width="32" height="4" fill="#fff"/>
                            </svg>
                        </div>
                        <div class="nike-payment-info">
                            <div class="nike-payment-name">Karta płatnicza</div>
                            <div class="nike-payment-desc">Visa, Mastercard, Maestro</div>
                        </div>
                        <div class="nike-payment-check">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                    </div>
                </label>

                <!-- Przelew -->
                <label class="nike-payment-method" data-method="transfer">
                    <input type="radio" name="payment_method" value="transfer">
                    <div class="nike-payment-content">
                        <div class="nike-payment-icon">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <path d="M8 16h24M8 12h24M8 8l16-4 16 4v24H8V8z" stroke="#111" stroke-width="2" fill="none"/>
                            </svg>
                        </div>
                        <div class="nike-payment-info">
                            <div class="nike-payment-name">Przelew bankowy</div>
                            <div class="nike-payment-desc">Szybki przelew online</div>
                        </div>
                        <div class="nike-payment-check">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                    </div>
                </label>

                <!-- PayPo -->
                <label class="nike-payment-method" data-method="paypo">
                    <input type="radio" name="payment_method" value="paypo">
                    <div class="nike-payment-content">
                        <div class="nike-payment-icon">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="18" fill="#111"/>
                                <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#fff" font-size="10" font-weight="bold">PP</text>
                            </svg>
                        </div>
                        <div class="nike-payment-info">
                            <div class="nike-payment-name">PayPo</div>
                            <div class="nike-payment-desc">Kup teraz, zapłać za 30 dni</div>
                        </div>
                        <div class="nike-payment-check">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                    </div>
                </label>

                <!-- Sandbox Notice -->
                <div class="nike-sandbox-notice">
                    <div class="nike-sandbox-title">Tryb testowy (Sandbox)</div>
                    <div class="nike-sandbox-text">
                        To jest symulacja płatności. Żadne prawdziwe środki nie zostaną pobrane. 
                        Po kliknięciu "Zapłać" płatność zostanie automatycznie zatwierdzona.
                    </div>
                </div>

                <!-- Buttons -->
                <div class="nike-buttons">
                    <a href="{{ route('payu.cancel', $order) }}" class="nike-btn nike-btn-secondary">
                        Anuluj
                    </a>
                    <button type="submit" class="nike-btn nike-btn-primary">
                        Zapłać {{ number_format($order->total_price, 2) }} PLN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <div class="nike-footer">
        <p class="nike-footer-text">Płatność zabezpieczona przez PayU | SSL 256-bit</p>
    </div>
</div>

<script>
    // Handle payment method selection
    document.querySelectorAll('.nike-payment-method').forEach(label => {
        label.addEventListener('click', function() {
            // Remove selected class from all
            document.querySelectorAll('.nike-payment-method').forEach(l => {
                l.classList.remove('selected');
            });
            // Add selected class to clicked
            this.classList.add('selected');
            // Check the radio
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
</script>
@endsection
