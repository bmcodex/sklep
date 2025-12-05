# Dokumentacja Techniczna Sklepu BMCODEX

## 1. Struktura Bazy Danych

Baza danych sklepu oparta jest na silniku MySQL i składa się z 8 głównych tabel. Poniżej znajduje się opis każdej z nich.

| Nazwa Tabeli | Opis |
|---|---|
| `users` | Przechowuje dane użytkowników (klienci i administratorzy). |
| `products` | Główna tabela z produktami, zawiera m.in. nazwę, opis, cenę, stan magazynowy. |
| `categories` | Kategorie produktów, powiązane z tabelą `products`. |
| `orders` | Zamówienia złożone przez klientów. |
| `order_items` | Pozycje w zamówieniach, powiązane z `orders` i `products`. |
| `cart_items` | Produkty w koszyku, powiązane z sesją lub użytkownikiem. |
| `favorites` | Ulubione produkty użytkowników. |
| `password_reset_tokens` | Tokeny do resetowania hasła. |

### Klucze Obce i Relacje

- `orders.user_id` -> `users.id` (jedno zamówienie należy do jednego użytkownika)
- `order_items.order_id` -> `orders.id` (jedna pozycja należy do jednego zamówienia)
- `order_items.product_id` -> `products.id` (jedna pozycja dotyczy jednego produktu)
- `products.category_id` -> `categories.id` (jeden produkt należy do jednej kategorii)
- `cart_items.user_id` -> `users.id` (koszyk może należeć do użytkownika)
- `favorites.user_id` -> `users.id` (ulubione należą do użytkownika)

## 2. Widoki (Views)

System nie wykorzystuje widoków bazodanowych (SQL Views). Cała logika prezentacji danych jest realizowana po stronie aplikacji w widokach Blade.

### Główne Widoki Blade (`resources/views`)

| Nazwa Pliku | Opis |
|---|---|
| `home.blade.php` | Strona główna, wyświetla listę produktów. |
| `products/show.blade.php` | Szczegóły pojedynczego produktu. |
| `cart/index.blade.php` | Widok koszyka z produktami. |
| `checkout.blade.php` | Formularz składania zamówienia. |
| `payu-sandbox.blade.php` | Formularz płatności PayU w trybie testowym. |
| `order-confirmation.blade.php` | Potwierdzenie złożenia zamówienia. |
| `admin/dashboard.blade.php` | Panel administracyjny. |

## 3. Wyzwalacze (Triggers)

Baza danych **nie używa wyzwalaczy (triggers)**. Cała logika biznesowa, taka jak aktualizacja stanu magazynowego po złożeniu zamówienia, jest zaimplementowana w kontrolerach Laravel.

### Przykład: Aktualizacja Stanu Magazynowego

W kontrolerze `OrderController` w metodzie `store`, po pomyślnym zapisaniu zamówienia, następuje iteracja po produktach w koszyku i zmniejszenie wartości `stock` dla każdego z nich:

```php
foreach ($cartItems as $item) {
    $product = $item->product;
    $product->decrement(\'stock\', $item->quantity);
}
```

## 4. Funkcje Usprawniające Działanie Sklepu

### Logika Aplikacji (Kontrolery Laravel)

- **Automatyczne czyszczenie koszyka:** Po złożeniu zamówienia, koszyk jest automatycznie czyszczony dla danego użytkownika lub sesji.
- **Walidacja danych:** Wszystkie dane wejściowe (formularze, parametry URL) są walidowane w kontrolerach, co zapobiega błędom i atakom.
- **System autoryzacji:** Dostęp do panelu admina i danych konta jest chroniony przez middleware `auth`.
- **Relacje Eloquent:** Wykorzystanie relacji w modelach (np. `Order` ma relację `hasMany` do `OrderItem`) upraszcza zapytania i poprawia czytelność kodu.

### Funkcje Bazy Danych

- **Indeksy:** Na kluczowych kolumnach (np. `user_id`, `status`, `created_at` w tabeli `orders`) założone są indeksy, co znacznie przyspiesza wyszukiwanie i sortowanie danych.
- **Transakcje:** Proces składania zamówienia jest opakowany w transakcję bazodanową (`DB::beginTransaction()`, `DB::commit()`, `DB::rollBack()`). Gwarantuje to, że zamówienie zostanie zapisane tylko wtedy, gdy wszystkie operacje (zapis zamówienia, aktualizacja stanu magazynowego, czyszczenie koszyka) zakończą się sukcesem. W przeciwnym razie wszystkie zmiany są wycofywane.

### Podsumowanie

Sklep BMCODEX wykorzystuje standardowe mechanizmy frameworka Laravel do zarządzania logiką biznesową i integralnością danych. Nie ma potrzeby stosowania zaawansowanych funkcji bazodanowych, takich jak widoki czy wyzwalacze, ponieważ cała logika jest efektywnie zarządzana na poziomie aplikacji.
