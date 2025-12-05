# Płatności w Trybie Sandbox - Sklep BMCodex

## Wprowadzenie

Tryb sandbox (piaskownica) to środowisko testowe, które pozwala na testowanie integracji płatności bez przeprowadzania rzeczywistych transakcji finansowych. W sklepie BMCodex można zintegrować płatności w trybie sandbox, aby przetestować proces zakupowy przed uruchomieniem produkcyjnym.

---

## Popularne Bramki Płatności w Polsce

### 1. Przelewy24 (Sandbox)

**Przelewy24** to jedna z najpopularniejszych bramek płatności w Polsce, obsługująca płatności kartą, przelewem bankowym, BLIK i inne.

#### Dane testowe Przelewy24 Sandbox:

| Parametr | Wartość |
|---|---|
| **URL Sandbox** | `https://sandbox.przelewy24.pl` |
| **Merchant ID** | Testowy ID otrzymany po rejestracji |
| **POS ID** | Testowy POS ID |
| **CRC Key** | Testowy klucz CRC |
| **API Key** | Testowy klucz API |

#### Testowe dane karty kredytowej:

| Pole | Wartość |
|---|---|
| **Numer karty** | `4444 3333 2222 1111` |
| **Data ważności** | Dowolna przyszła data (np. `12/25`) |
| **CVV** | `123` |
| **Wynik** | Transakcja zakończona sukcesem |

#### Testowe dane BLIK:

| Pole | Wartość |
|---|---|
| **Kod BLIK** | `777123` |
| **Wynik** | Transakcja zakończona sukcesem |

#### Przykładowa integracja w Laravel:

```php
// config/przelewy24.php
return [
    'merchant_id' => env('P24_MERCHANT_ID'),
    'pos_id' => env('P24_POS_ID'),
    'crc_key' => env('P24_CRC_KEY'),
    'api_key' => env('P24_API_KEY'),
    'mode' => env('P24_MODE', 'sandbox'), // sandbox lub live
];

// .env (Sandbox)
P24_MERCHANT_ID=your_test_merchant_id
P24_POS_ID=your_test_pos_id
P24_CRC_KEY=your_test_crc_key
P24_API_KEY=your_test_api_key
P24_MODE=sandbox
```

---

### 2. PayU (Sandbox)

**PayU** to kolejna popularna bramka płatności w Polsce, oferująca szybkie płatności online.

#### Dane testowe PayU Sandbox:

| Parametr | Wartość |
|---|---|
| **URL Sandbox** | `https://secure.snd.payu.com` |
| **POS ID** | `300746` |
| **Second Key (MD5)** | `b6ca15b0d1020e8094d9b5f8d163db54` |
| **OAuth Client ID** | `300746` |
| **OAuth Client Secret** | `2ee86a66e5d97e3fadc400c9f19b065d` |

#### Testowe dane karty kredytowej:

| Pole | Wartość |
|---|---|
| **Numer karty** | `4444 3333 2222 1111` |
| **Data ważności** | `12/25` |
| **CVV** | `123` |
| **Wynik** | Transakcja zakończona sukcesem |

#### Przykładowa integracja w Laravel:

```php
// config/payu.php
return [
    'pos_id' => env('PAYU_POS_ID'),
    'second_key' => env('PAYU_SECOND_KEY'),
    'oauth_client_id' => env('PAYU_OAUTH_CLIENT_ID'),
    'oauth_client_secret' => env('PAYU_OAUTH_CLIENT_SECRET'),
    'mode' => env('PAYU_MODE', 'sandbox'),
];

// .env (Sandbox)
PAYU_POS_ID=300746
PAYU_SECOND_KEY=b6ca15b0d1020e8094d9b5f8d163db54
PAYU_OAUTH_CLIENT_ID=300746
PAYU_OAUTH_CLIENT_SECRET=2ee86a66e5d97e3fadc400c9f19b065d
PAYU_MODE=sandbox
```

---

### 3. Stripe (Sandbox/Test Mode)

**Stripe** to międzynarodowa bramka płatności, która również działa w Polsce i oferuje tryb testowy.

#### Dane testowe Stripe:

| Parametr | Wartość |
|---|---|
| **URL** | `https://api.stripe.com` |
| **Publishable Key (Test)** | `pk_test_...` (z dashboardu Stripe) |
| **Secret Key (Test)** | `sk_test_...` (z dashboardu Stripe) |

#### Testowe numery kart:

| Numer karty | Wynik |
|---|---|
| `4242 4242 4242 4242` | Sukces |
| `4000 0000 0000 0002` | Odrzucona karta |
| `4000 0000 0000 9995` | Niewystarczające środki |

#### Przykładowa integracja w Laravel:

```php
// .env (Test Mode)
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
```

---

### 4. PayPal (Sandbox)

**PayPal** oferuje tryb sandbox do testowania płatności.

#### Dane testowe PayPal Sandbox:

| Parametr | Wartość |
|---|---|
| **URL Sandbox** | `https://www.sandbox.paypal.com` |
| **Client ID** | Testowy Client ID z dashboardu |
| **Secret** | Testowy Secret z dashboardu |

#### Testowe konta PayPal:

Możesz stworzyć testowe konta kupującego i sprzedającego w PayPal Sandbox Dashboard.

#### Przykładowa integracja:

```php
// .env (Sandbox)
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=your_sandbox_client_id
PAYPAL_SANDBOX_SECRET=your_sandbox_secret
```

---

## Implementacja w Sklepie BMCodex

### ⚠️ WAŻNE: Przed przejściem do płatności

**Przed rozpoczęciem procesu płatności należy włączyć PayU w konfiguracji sklepu!**

Upewnij się, że:
1. Dane PayU są poprawnie skonfigurowane w pliku `.env`
2. Kontroler PayU jest załadowany i dostępny
3. Routing dla PayU jest aktywny
4. Tryb sandbox jest włączony dla testów

### Krok 1: Wybór bramki płatności

Wybierz bramkę płatności odpowiednią dla Twojego sklepu (np. Przelewy24 lub PayU dla polskich klientów).

### Krok 2: Instalacja pakietu

Zainstaluj odpowiedni pakiet Laravel dla wybranej bramki:

```bash
# Przelewy24
composer require devpark/przelewy24-laravel

# PayU
composer require devpark/payu-laravel

# Stripe
composer require stripe/stripe-php
```

### Krok 3: Konfiguracja

Skonfiguruj plik `.env` z danymi testowymi (sandbox).

### Krok 4: Utworzenie kontrolera płatności

```php
// app/Http/Controllers/PaymentController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function initiate(Request $request)
    {
        // Logika inicjowania płatności
        // Przekierowanie do bramki płatności
    }

    public function callback(Request $request)
    {
        // Obsługa powrotu z bramki płatności
        // Weryfikacja statusu płatności
    }

    public function webhook(Request $request)
    {
        // Obsługa powiadomień z bramki płatności
    }
}
```

### Krok 5: Routing

```php
// routes/web.php
Route::post('/payment/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
```

---

## Testowanie Płatności

### Scenariusze testowe:

1. **Płatność kartą - sukces**
   - Użyj testowego numeru karty dla sukcesu
   - Sprawdź czy zamówienie zostało oznaczone jako opłacone

2. **Płatność kartą - odrzucenie**
   - Użyj testowego numeru karty dla odrzucenia
   - Sprawdź czy zamówienie pozostaje nieopłacone

3. **Płatność BLIK - sukces**
   - Użyj testowego kodu BLIK
   - Sprawdź czy płatność została zrealizowana

4. **Webhook/Callback**
   - Sprawdź czy system poprawnie odbiera powiadomienia z bramki
   - Zweryfikuj aktualizację statusu zamówienia

---

## Przejście do Trybu Produkcyjnego

Gdy testy zostaną zakończone pomyślnie:

1. Zmień `mode` z `sandbox` na `live` w pliku `.env`
2. Zamień testowe klucze API na produkcyjne
3. Przetestuj ponownie z małą kwotą rzeczywistą
4. Uruchom monitoring płatności

---

## Podsumowanie

Tryb sandbox pozwala na bezpieczne testowanie integracji płatności bez ryzyka finansowego. Przed uruchomieniem produkcyjnym należy dokładnie przetestować wszystkie scenariusze płatności i upewnić się, że system poprawnie obsługuje zarówno udane, jak i nieudane transakcje.

### ⚠️ Pamiętaj!

**Przed każdą płatnością upewnij się, że PayU jest włączone w konfiguracji sklepu!** Bez poprawnej konfiguracji proces płatności nie będzie działał.

---
 
**Data:** 5 grudnia 2024
