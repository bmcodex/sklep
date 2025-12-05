# BMCODEX - Dokumentacja Techniczna

## Performance Without Limits

---

## 1. Przegląd Projektu

**BMCODEX** to pełnofunkcyjny sklep internetowy do sprzedaży części do tuningu samochodów. Aplikacja zbudowana jest w oparciu o Laravel 11 (PHP 8+) z bazą danych MySQL 8+.

### Główne funkcjonalności:

- **Katalog produktów** z filtrowaniem po kategorii i cenie
- **System koszyka** z obsługą gościa i zalogowanego użytkownika
- **Rejestracja i logowanie** z resetem hasła
- **Panel klienta** z historią zamówień i ulubionymi produktami
- **Panel administracyjny** do zarządzania produktami, zamówieniami i użytkownikami
- **Zaawansowane funkcje SQL** (widoki, wyzwalacze, transakcje)

---

## 2. Architektura Aplikacji

### Struktura projektu:

```
bmcodex/
├── app/
│   ├── Models/              # Modele Eloquent
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Favorite.php
│   │   └── CartItem.php
│   └── Http/
│       └── Controllers/     # Kontrolery
│           ├── ProductController.php
│           ├── CartController.php
│           ├── OrderController.php
│           ├── AuthController.php
│           └── AdminController.php
├── database/
│   ├── migrations/          # Migracje bazy danych
│   ├── seeders/             # Seedery z danymi
│   └── bmcodex_schema.sql   # Pełny skrypt SQL
├── resources/
│   └── views/               # Szablony Blade
├── routes/
│   └── web.php              # Definicje tras
└── public/
    └── images/              # Obrazy produktów
```

### Warstwa aplikacji:

1. **Frontend** - szablony Blade z HTML5, CSS3, JavaScript
2. **Backend** - kontrolery Laravel przetwarzające żądania
3. **Model** - Eloquent ORM do komunikacji z bazą danych
4. **Baza danych** - MySQL 8+ z widokami, wyzwalaczami i transakcjami

---

## 3. Baza Danych

### Tabele:

| Tabela | Opis |
|--------|------|
| `users` | Użytkownicy (klienci, pracownicy, admini) |
| `categories` | Kategorie produktów |
| `products` | Produkty do sprzedaży |
| `orders` | Zamówienia klientów |
| `order_items` | Pozycje w zamówieniach |
| `favorites` | Ulubione produkty użytkowników |
| `cart_items` | Produkty w koszyku |

### Widoki SQL (Views):

#### 1. `sales_report` - Raport sprzedaży
```sql
SELECT 
    DATE(o.created_at) as sale_date,
    COUNT(o.id) as total_orders,
    SUM(o.total_price) as total_revenue
FROM orders o
WHERE o.status != 'cancelled'
GROUP BY DATE(o.created_at);
```

**Zastosowanie:** Analiza sprzedaży dziennej, tygodniowej, miesięcznej. Widok umożliwia szybkie uzyskanie statystyk bez konieczności pisania złożonych zapytań.

#### 2. `product_stats` - Statystyki produktów
```sql
SELECT 
    p.id, p.name, p.price,
    COUNT(DISTINCT oi.order_id) as times_sold,
    SUM(oi.quantity) as total_quantity_sold,
    COUNT(DISTINCT f.id) as favorite_count
FROM products p
LEFT JOIN order_items oi ON p.id = oi.product_id
LEFT JOIN favorites f ON p.id = f.product_id
GROUP BY p.id;
```

**Zastosowanie:** Identyfikacja najpopularniejszych produktów, analiza sprzedaży produktów, rekomendacje dla klientów.

#### 3. `top_products` - Top 10 produktów
**Zastosowanie:** Wyświetlanie bestselerów na stronie głównej.

#### 4. `user_order_history` - Historia zamówień użytkownika
**Zastosowanie:** Panel klienta - szybki dostęp do historii zamówień.

### Wyzwalacze SQL (Triggers):

#### 1. `update_stock_on_order_insert`
```sql
AFTER INSERT ON order_items
UPDATE products SET stock = stock - NEW.quantity
WHERE id = NEW.product_id;
```

**Cel:** Automatyczne zmniejszenie stanu magazynowego po dodaniu produktu do zamówienia.

#### 2. `restore_stock_on_order_delete`
```sql
AFTER DELETE ON order_items
UPDATE products SET stock = stock + OLD.quantity
WHERE id = OLD.product_id;
```

**Cel:** Przywrócenie stanu magazynowego po usunięciu pozycji z zamówienia.

#### 3. `restore_stock_on_order_cancel`
```sql
AFTER UPDATE ON orders
IF NEW.status = 'cancelled' AND OLD.status != 'cancelled' THEN
    UPDATE products p
    SET p.stock = p.stock + (
        SELECT SUM(oi.quantity)
        FROM order_items oi
        WHERE oi.order_id = NEW.id
    );
END IF;
```

**Cel:** Przywrócenie całego stanu magazynowego po anulowaniu zamówienia.

### Funkcje SQL:

#### 1. `calculate_order_total(order_id)`
```sql
CREATE FUNCTION calculate_order_total(order_id INT) 
RETURNS DECIMAL(10, 2)
BEGIN
    DECLARE total DECIMAL(10, 2);
    SELECT SUM(quantity * price_per_item) INTO total
    FROM order_items
    WHERE order_items.order_id = order_id;
    RETURN IFNULL(total, 0);
END;
```

**Zastosowanie:** Szybkie obliczenie całkowitej wartości zamówienia.

#### 2. `is_product_available(product_id, required_quantity)`
```sql
CREATE FUNCTION is_product_available(product_id INT, required_quantity INT)
RETURNS BOOLEAN
BEGIN
    DECLARE available INT;
    SELECT stock INTO available FROM products WHERE id = product_id;
    RETURN IFNULL(available >= required_quantity, FALSE);
END;
```

**Zastosowanie:** Sprawdzenie dostępności produktu przed dodaniem do zamówienia.

### Transakcje:

Transakcje są używane w procesie zamawiania:

```php
DB::transaction(function () {
    // 1. Sprawdzenie dostępności produktów
    // 2. Utworzenie zamówienia
    // 3. Dodanie pozycji do zamówienia
    // 4. Aktualizacja stanu magazynowego
    // 5. Wysłanie emaila potwierdzającego
});
```

**Cel:** Zapewnienie spójności danych - jeśli cokolwiek pójdzie nie tak, cała transakcja zostaje wycofana.

---

## 4. Modele Eloquent

### User
- Relacje: `orders()`, `favorites()`, `cartItems()`
- Metody: `isAdmin()`, `isEmployee()`, `isUser()`
- Implementuje: `MustVerifyEmail` - wymagane potwierdzenie emaila

### Product
- Relacje: `category()`, `orderItems()`, `favorites()`, `cartItems()`
- Metody: `isAvailable($quantity)`
- Casts: `price` jako `decimal:2`

### Order
- Relacje: `user()`, `items()`
- Metody: `isPending()`, `isProcessing()`, `isShipped()`, `isDelivered()`, `isCancelled()`
- Obsługuje zamówienia gościa (`guest_email`)

### OrderItem
- Relacje: `order()`, `product()`
- Metody: `getTotalPrice()`

### Favorite
- Relacje: `user()`, `product()`
- Unikalne: `(user_id, product_id)` - każdy użytkownik może ulubić produkt tylko raz

### CartItem
- Relacje: `user()`, `product()`
- Metody: `getTotalPrice()`

---

## 5. Kontrolery

### ProductController
- `index()` - wyświetla listę produktów z filtrowaniem
- `show($product)` - szczegóły produktu
- `byCategory($category)` - produkty z kategorii
- `favorites()` - ulubione produkty użytkownika
- `addFavorite($product)` - dodanie do ulubionych
- `removeFavorite($product)` - usunięcie z ulubionych

### CartController
- `index()` - wyświetla zawartość koszyka
- `add($product)` - dodanie produktu do koszyka
- `remove($cartItem)` - usunięcie z koszyka
- `update($cartItem)` - zmiana ilości
- `clear()` - wyczyszczenie koszyka

### OrderController
- `checkout()` - strona zamawiania
- `store()` - zapisanie zamówienia (transakcja!)
- `confirmation($order)` - potwierdzenie zamówienia
- `dashboard()` - panel klienta
- `userOrders()` - historia zamówień
- `show($order)` - szczegóły zamówienia

### AuthController
- `showRegister()` - formularz rejestracji
- `register()` - przetworzenie rejestracji
- `showLogin()` - formularz logowania
- `login()` - przetworzenie logowania
- `logout()` - wylogowanie
- `showForgotPassword()` - formularz resetu hasła
- `sendResetLink()` - wysłanie linka resetującego
- `showResetPassword($token)` - formularz nowego hasła
- `resetPassword()` - ustawienie nowego hasła

### AdminController
- `dashboard()` - panel administracyjny
- `products()` - lista produktów
- `createProduct()`, `storeProduct()` - dodawanie produktu
- `editProduct()`, `updateProduct()` - edycja produktu
- `deleteProduct()` - usunięcie produktu
- `categories()` - zarządzanie kategoriami
- `orders()` - lista zamówień
- `updateOrderStatus()` - zmiana statusu zamówienia
- `users()` - lista użytkowników
- `reports()` - raporty sprzedaży

---

## 6. Trasy (Routes)

### Publiczne:
- `GET /` - strona główna
- `GET /products` - lista produktów
- `GET /products/{product}` - szczegóły produktu
- `GET /category/{category}` - produkty z kategorii

### Autentykacja:
- `GET /register` - rejestracja
- `POST /register` - przetworzenie rejestracji
- `GET /login` - logowanie
- `POST /login` - przetworzenie logowania
- `POST /logout` - wylogowanie

### Koszyk:
- `GET /cart` - zawartość koszyka
- `POST /cart/add/{product}` - dodanie do koszyka
- `POST /cart/remove/{cartItem}` - usunięcie z koszyka

### Zamówienia:
- `GET /checkout` - strona zamawiania
- `POST /order/store` - zapisanie zamówienia

### Panel klienta (wymagane logowanie):
- `GET /dashboard` - panel
- `GET /orders` - historia zamówień
- `GET /orders/{order}` - szczegóły zamówienia
- `GET /favorites` - ulubione produkty

### Panel administracyjny (wymagane logowanie + rola admin):
- `GET /admin/dashboard` - panel admina
- `GET /admin/products` - zarządzanie produktami
- `GET /admin/orders` - zarządzanie zamówieniami
- `GET /admin/users` - zarządzanie użytkownikami
- `GET /admin/reports` - raporty

---

## 7. Instalacja i Wdrożenie

### Wymagania:
- PHP 8.0+
- MySQL 8.0+
- Composer
- Node.js (opcjonalnie, dla Vite)

### Kroki instalacji:

```bash
# 1. Klonowanie repozytorium
git clone https://github.com/MichalNurzynski/bmcodex.git
cd bmcodex

# 2. Instalacja zależności
composer install

# 3. Konfiguracja .env
cp .env.example .env
# Edytuj .env - ustaw dane bazy danych

# 4. Generowanie klucza aplikacji
php artisan key:generate

# 5. Uruchomienie migracji
php artisan migrate

# 6. Wypełnienie bazy danymi
php artisan db:seed

# 7. Uruchomienie serwera
php artisan serve
```

### Dane dostępowe do testowania:

| Rola | Email | Hasło |
|------|-------|-------|
| Admin | admin@bmcodex.com | password123 |
| Klient | jan.kowalski@example.com | password123 |
| Klient | anna.nowak@example.com | password123 |

---

## 8. Bezpieczeństwo

- **Haszowanie haseł** - Laravel Hashing (bcrypt)
- **CSRF Protection** - tokeny CSRF na wszystkich formularzach
- **SQL Injection** - Eloquent ORM chroni przed SQL injection
- **XSS Protection** - Blade templates automatycznie escapują dane
- **Autoryzacja** - Middleware do sprawdzenia uprawnień
- **Email Verification** - Potwierdzenie emaila przy rejestracji
- **Password Reset** - Bezpieczne linki resetujące z tokenami

---

## 9. Optymalizacja Wydajności

### Indeksy bazy danych:
- `idx_email` na `users.email`
- `idx_role` na `users.role`
- `idx_category` na `products.category_id`
- `idx_sku` na `products.sku`
- `idx_price` na `products.price`
- `idx_user` na `orders.user_id`
- `idx_status` na `orders.status`

### Eager Loading (Eloquent):
```php
// Zamiast N+1 queries
Product::with('category', 'favorites')->get();
Order::with('items.product', 'user')->get();
```

### Caching:
```php
Cache::remember('products', 3600, function () {
    return Product::all();
});
```

---

## 10. Rozszerzenia i Integracje

### Planowane:
- **PayU Integration** - płatności online (Sandbox mode)
- **Email Notifications** - powiadomienia mailowe
- **SMS Notifications** - powiadomienia SMS
- **Recenzje produktów** - system ocen
- **Rekomendacje** - algorytm rekomendacji
- **Analytics** - śledzenie użytkowników

---

## 11. Testowanie

### Testy jednostkowe:
```bash
php artisan test
```

### Testy funkcjonalne:
```bash
php artisan test --filter=OrderTest
```

---

## 12. Troubleshooting

### Problem: "Connections using insecure transport are prohibited"
**Rozwiązanie:** Dodaj `?ssl=true` do `DATABASE_URL` lub użyj lokalnego MySQL.

### Problem: "CSRF token mismatch"
**Rozwiązanie:** Upewnij się, że formularz zawiera `@csrf` token.

### Problem: "Class not found"
**Rozwiązanie:** Uruchom `composer dump-autoload`

---

## 13. Kontakt i Wsparcie

**Autor:** Michał Nurzyński  
**Email:** admin@bmcodex.com  
**GitHub:** https://github.com/MichalNurzynski/bmcodex

---

*Ostatnia aktualizacja: 30 października 2025*
