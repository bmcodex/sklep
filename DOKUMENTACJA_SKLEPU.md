'''
# Dokumentacja Sklepu BMCODEX

## 1. Widoki (Views)

Widoki w Laravelu są odpowiedzialne za prezentację danych i interfejs użytkownika. Znajdują się w katalogu `resources/views`.

| Plik | Opis |
|---|---|
| `welcome.blade.php` | Strona główna sklepu, wyświetla produkty i filtry. |
| `home.blade.php` | Główny layout strony po zalogowaniu. |
| `products/show.blade.php` | Widok szczegółów pojedynczego produktu. |
| `cart/index.blade.php` | Widok koszyka z produktami. |
| `checkout.blade.php` | Strona finalizacji zamówienia. |
| `order-confirmation.blade.php` | Strona z potwierdzeniem złożenia zamówienia. |
| `layouts/app.blade.php` | Główny szablon aplikacji, zawiera nawigację, stopkę itp. |
| `admin/dashboard.blade.php` | Panel administracyjny. |
| `auth/login.blade.php` | Strona logowania. |
| `auth/register.blade.php` | Strona rejestracji. |

## 2. Wyzwalacze (Triggers)

W aplikacji nie zidentyfikowano żadnych wyzwalaczy bazodanowych. Logika biznesowa jest zaimplementowana w kontrolerach i modelach Laravel.

## 3. Inne Funkcje

### Kontrolery (Controllers)

Kontrolery obsługują logikę aplikacji. Główne kontrolery to:

- `ProductController` - obsługa produktów (wyświetlanie, filtrowanie).
- `CartController` - obsługa koszyka (dodawanie, usuwanie, aktualizacja).
- `OrderController` - obsługa zamówień.
- `PayUController` - obsługa płatności PayU.

### Modele (Models)

Modele Eloquent ORM odpowiadają za interakcję z bazą danych.

- `Product` - model produktu.
- `Category` - model kategorii produktu.
- `Order` - model zamówienia.
- `User` - model użytkownika.

### Routing

Pliki w katalogu `routes` definiują adresy URL i przypisane do nich akcje kontrolerów.

- `web.php` - główne trasy aplikacji.
- `payu.php` - trasy związane z płatnościami PayU.
'''
