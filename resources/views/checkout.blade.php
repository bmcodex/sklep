@extends('layouts.app')

@section('title', 'Kasa - BMCODEX')

@section('content')
<div class="container" style="max-width: 1000px; margin: 3rem auto;">
    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-light); border-bottom: 2px solid var(--primary-color); padding-bottom: 1rem;">
        Finalizacja zamówienia
    </h1>

    <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem;">
        <!-- Formularz zamówienia -->
        <div>
            <form action="{{ route('order.store') }}" method="POST">
                @csrf
                
                <!-- Dane do wysyłki -->
                <div style="background-color: var(--light-gray); padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--text-light);">Adres dostawy</h2>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light);">Adres dostawy *</label>
                        <textarea name="shipping_address" required rows="4" style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <span style="color: #dc3545; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Dane do faktury -->
                <div style="background-color: var(--light-gray); padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--text-light);">Adres do faktury</h2>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: flex; align-items: center; color: var(--text-light); cursor: pointer;">
                            <input type="checkbox" id="same_address" checked style="margin-right: 0.5rem;">
                            Taki sam jak adres dostawy
                        </label>
                    </div>
                    
                    <div id="billing_address_field" style="display: none;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light);">Adres do faktury *</label>
                        <textarea name="billing_address" rows="4" style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">{{ old('billing_address') }}</textarea>
                        @error('billing_address')
                            <span style="color: #dc3545; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @guest
                <!-- Email dla gości -->
                <div style="background-color: var(--light-gray); padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--text-light);">Dane kontaktowe</h2>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light);">Email *</label>
                        <input type="email" name="guest_email" required value="{{ old('guest_email') }}" style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                        @error('guest_email')
                            <span style="color: #dc3545; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @endguest

                <button type="submit" style="width: 100%; padding: 1.2rem; background-color: var(--primary-color); color: var(--dark-bg); border: none; border-radius: 4px; font-size: 1.3rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease;">
                    Złóż zamówienie
                </button>
            </form>
        </div>

        <!-- Podsumowanie zamówienia -->
        <div>
            <div style="background-color: var(--light-gray); padding: 2rem; border-radius: 8px; position: sticky; top: 2rem;">
                <h2 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--text-light); border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem;">
                    Twoje zamówienie
                </h2>

                <div style="margin-bottom: 1.5rem;">
                    @foreach($cartItems as $item)
                        <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #333; color: var(--text-light);">
                            <div>
                                <div style="font-weight: 600;">{{ $item->product->name }}</div>
                                <div style="color: #999; font-size: 0.9rem;">Ilość: {{ $item->quantity }}</div>
                            </div>
                            <div style="font-weight: 700; color: var(--primary-color);">
                                {{ number_format($item->product->price * $item->quantity, 2) }} PLN
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #333; color: var(--text-light);">
                    <span>Produkty</span>
                    <span>{{ number_format($total, 2) }} PLN</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #333; color: var(--text-light);">
                    <span>Dostawa</span>
                    <span style="color: #28a745; font-weight: 600;">Gratis</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 1.5rem 0; font-size: 1.5rem; font-weight: 700; color: var(--primary-color);">
                    <span>Razem</span>
                    <span>{{ number_format($total, 2) }} PLN</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('same_address').addEventListener('change', function() {
    const billingField = document.getElementById('billing_address_field');
    const billingTextarea = billingField.querySelector('textarea');
    const shippingTextarea = document.querySelector('textarea[name="shipping_address"]');
    
    if (this.checked) {
        billingField.style.display = 'none';
        billingTextarea.value = shippingTextarea.value;
        billingTextarea.removeAttribute('required');
    } else {
        billingField.style.display = 'block';
        billingTextarea.setAttribute('required', 'required');
    }
});

// Synchronizuj adresy gdy checkbox jest zaznaczony
document.querySelector('textarea[name="shipping_address"]').addEventListener('input', function() {
    const sameAddress = document.getElementById('same_address');
    if (sameAddress.checked) {
        document.querySelector('textarea[name="billing_address"]').value = this.value;
    }
});
</script>
@endsection
