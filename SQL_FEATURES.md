# BMCODEX - Zaawansowane Funkcje SQL

## Widoki (Views)

### 1. `sales_report` - Raport Sprzedaży

**Lokalizacja:** `database/bmcodex_schema.sql` (linia ~120)

**SQL:**
```sql
CREATE OR REPLACE VIEW sales_report AS
SELECT 
    DATE(o.created_at) as sale_date,
    COUNT(o.id) as total_orders,
    COUNT(DISTINCT o.user_id) as unique_customers,
    SUM(o.total_price) as total_revenue,
    AVG(o.total_price) as avg_order_value
FROM orders o
WHERE o.status != 'cancelled'
GROUP BY DATE(o.created_at)
ORDER BY sale_date DESC;
```

**Zastosowanie w aplikacji:**
- Panel administracyjny: `AdminController@reports()`
- Wyświetlanie statystyk sprzedaży dziennej
- Analiza trendu sprzedaży
- Raport przychodu

**Przykład użycia w PHP:**
```php
$salesReport = DB::table('sales_report')
    ->whereBetween('sale_date', [$startDate, $endDate])
    ->get();
```

**Korzyści:**
- Szybkie obliczenie statystyk bez konieczności agregacji w aplikacji
- Zmniejszenie obciążenia serwera aplikacji
- Łatwe eksportowanie danych do raportów

---

### 2. `product_stats` - Statystyki Produktów

**Lokalizacja:** `database/bmcodex_schema.sql` (linia ~135)

**SQL:**
```sql
CREATE OR REPLACE VIEW product_stats AS
SELECT 
    p.id,
    p.name,
    p.price,
    p.stock,
    c.name as category,
    COUNT(DISTINCT oi.order_id) as times_sold,
    SUM(oi.quantity) as total_quantity_sold,
    COUNT(DISTINCT f.id) as favorite_count
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
LEFT JOIN order_items oi ON p.id = oi.product_id
LEFT JOIN favorites f ON p.id = f.product_id
GROUP BY p.id, p.name, p.price, p.stock, c.name;
```

**Zastosowanie w aplikacji:**
- Identyfikacja bestselerów
- Rekomendacje produktów
- Analiza popularności produktów
- Zarządzanie magazynem

**Przykład użycia w PHP:**
```php
$topProducts = DB::table('product_stats')
    ->orderBy('times_sold', 'desc')
    ->limit(10)
    ->get();

$lowStockProducts = DB::table('product_stats')
    ->where('stock', '<', 10)
    ->get();
```

**Korzyści:**
- Kompleksowe dane o każdym produkcie w jednym zapytaniu
- Łatwa identyfikacja produktów wymagających uzupełnienia magazynu
- Dane do systemu rekomendacji

---

### 3. `top_products` - Top 10 Produktów

**Lokalizacja:** `database/bmcodex_schema.sql` (linia ~155)

**SQL:**
```sql
CREATE OR REPLACE VIEW top_products AS
SELECT 
    p.id,
    p.name,
    p.price,
    COUNT(DISTINCT oi.order_id) as orders_count,
    SUM(oi.quantity) as total_sold
FROM products p
LEFT JOIN order_items oi ON p.id = oi.product_id
GROUP BY p.id, p.name, p.price
ORDER BY total_sold DESC
LIMIT 10;
```

**Zastosowanie w aplikacji:**
- Strona główna: wyświetlanie bestselerów
- Rekomendacje dla nowych użytkowników
- Marketing i promocje

**Przykład użycia w PHP:**
```php
$topProducts = DB::table('top_products')->get();
// Wyświetlenie na stronie głównej
return view('home', ['topProducts' => $topProducts]);
```

---

### 4. `user_order_history` - Historia Zamówień Użytkownika

**Lokalizacja:** `database/bmcodex_schema.sql` (linia ~170)

**SQL:**
```sql
CREATE OR REPLACE VIEW user_order_history AS
SELECT 
    u.id as user_id,
    u.email,
    u.first_name,
    u.last_name,
    o.id as order_id,
    o.total_price,
    o.status,
    COUNT(oi.id) as items_count,
    o.created_at as order_date
FROM users u
LEFT JOIN orders o ON u.id = o.user_id
LEFT JOIN order_items oi ON o.id = oi.order_id
GROUP BY u.id, o.id;
```

**Zastosowanie w aplikacji:**
- Panel klienta: historia zamówień
- Szybki dostęp do informacji o zamówieniach
- Raport dla administratora

**Przykład użycia w PHP:**
```php
$userHistory = DB::table('user_order_history')
    ->where('user_id', auth()->id())
    ->get();
```

---

## Wyzwalacze (Triggers)

### 1. `update_stock_on_order_insert` - Zmniejszenie Stanu Magazynowego

**Lokalizacja:** `database/bmcodex_schema.sql` (linia ~185)

**SQL:**
```sql
CREATE TRIGGER update_stock_on_order_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products 
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;
END;
```

**Cel:**
- Automatyczne zmniejszenie stanu magazynowego
- Zapewnienie spójności danych
- Brak potrzeby ręcznej aktualizacji w aplikacji

**Gdzie jest używane:**
- Proces zamawiania: `OrderController@store()`
- Automatycznie uruchamia się po dodaniu pozycji do zamówienia

**Korzyści:**
- Gwarancja, że stan magazynowy jest zawsze aktualny
- Niemożliwość "zapomnienia" o aktualizacji
- Bezpieczeństwo na poziomie bazy danych

---

### 2. `restore_stock_on_order_delete` - Przywrócenie Stanu

**Lokalizacja:** `database/bmcodex_schema.sql` (linia ~197)

**SQL:**
```sql
CREATE TRIGGER restore_stock_on_order_delete
AFTER DELETE ON order_items
FOR EACH ROW
BEGIN
    UPDATE products 
    SET stock = stock + OLD.quantity
    WHERE id = OLD.product_id;
END;
```

**Cel:**
- Przywrócenie stanu magazynowego po usunięciu pozycji
- Obsługa scenariusza: klient zmienia zdanie przed potwierdzeniem

**Gdzie jest używane:**
- Usuwanie produktu z koszyka: `CartController@remove()`
- Automatycznie uruchamia się po usunięciu pozycji

---

### 3. `restore_stock_on_order_cancel` - Przywrócenie przy Anulowaniu

**Lokalizacja:** `database/bmcodex_schema.sql` (linia ~209)

**SQL:**
```sql
CREATE TRIGGER restore_stock_on_order_cancel
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.status = 'cancelled' AND OLD.status != 'cancelled' THEN
        UPDATE products p
        SET p.stock = p.stock + (
            SELECT SUM(oi.quantity)
            FROM order_items oi
            WHERE oi.order_id = NEW.id
        )
        WHERE p.id IN (
            SELECT product_id FROM order_items WHERE order_id = NEW.id
        );
    END IF;
END;
```

**Cel:**
- Przywrócenie całego stanu magazynowego po anulowaniu zamówienia
- Obsługa scenariusza: admin anuluje zamówienie

**Gdzie jest używane:**
- Panel admina: zmiana statusu zamówienia na "cancelled"
- Automatycznie uruchamia się po zmianie statusu

**Korzyści:**
- Automatyczne przywrócenie stanu dla wszystkich produktów w zamówieniu
- Brak ryzyka utraty produktów z magazynu

---

## Funkcje (Functions)

### 1. `calculate_order_total()` - Obliczenie Wartości Zamówienia

**Lokalizacja:** `database/bmcodex_schema.sql` (linia ~230)

**SQL:**
```sql
CREATE FUNCTION calculate_order_total(order_id INT) 
RETURNS DECIMAL(10, 2)
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE total DECIMAL(10, 2);
    SELECT SUM(quantity * price_per_item) INTO total
    FROM order_items
    WHERE order_items.order_id = order_id;
    RETURN IFNULL(total, 0);
END;
```

**Zastosowanie:**
- Szybkie obliczenie całkowitej wartości zamówienia
- Weryfikacja wartości zamówienia

**Przykład użycia w SQL:**
```sql
SELECT order_id, calculate_order_total(order_id) as total
FROM orders;
```

**Korzyści:**
- Konsystentne obliczenia na poziomie bazy danych
- Eliminacja błędów zaokrąglenia
- Szybkość

---

### 2. `is_product_available()` - Sprawdzenie Dostępności

**Lokalizacja:** `database/bmcodex_schema.sql` (linia ~245)

**SQL:**
```sql
CREATE FUNCTION is_product_available(product_id INT, required_quantity INT)
RETURNS BOOLEAN
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE available INT;
    SELECT stock INTO available FROM products WHERE id = product_id;
    RETURN IFNULL(available >= required_quantity, FALSE);
END;
```

**Zastosowanie:**
- Sprawdzenie dostępności przed dodaniem do zamówienia
- Walidacja na poziomie bazy danych

**Przykład użycia w SQL:**
```sql
SELECT * FROM products 
WHERE is_product_available(id, 5) = TRUE;
```

**Korzyści:**
- Gwarancja, że nie będzie sprzedawany brak towaru
- Szybka walidacja

---

## Transakcje (Transactions)

### Proces Zamawiania

**Lokalizacja:** `app/Http/Controllers/OrderController.php` - metoda `store()`

**PHP:**
```php
DB::transaction(function () {
    // 1. Sprawdzenie dostępności produktów
    foreach ($cartItems as $item) {
        if (!$item->product->isAvailable($item->quantity)) {
            throw new Exception('Produkt niedostępny');
        }
    }
    
    // 2. Utworzenie zamówienia
    $order = Order::create([
        'user_id' => auth()->id(),
        'total_price' => $cartTotal,
        'status' => 'pending',
        'shipping_address' => $request->shipping_address,
        'billing_address' => $request->billing_address,
    ]);
    
    // 3. Dodanie pozycji do zamówienia
    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price_per_item' => $item->product->price,
        ]);
        // Wyzwalacz automatycznie zmniejsza stock
    }
    
    // 4. Wyczyszczenie koszyka
    CartItem::where('user_id', auth()->id())->delete();
    
    // 5. Wysłanie emaila
    Mail::send(new OrderConfirmation($order));
    
    return $order;
});
```

**Cel:**
- Atomowość operacji - wszystko lub nic
- Jeśli coś pójdzie nie tak, wszystko zostaje wycofane

**Korzyści:**
- Gwarancja spójności danych
- Brak częściowych zamówień
- Bezpieczeństwo finansowe

---

## Indeksy

**Lokalizacja:** `database/bmcodex_schema.sql` (linia ~250)

```sql
CREATE INDEX idx_order_user_date ON orders(user_id, created_at);
CREATE INDEX idx_product_category_price ON products(category_id, price);
CREATE INDEX idx_order_items_order_product ON order_items(order_id, product_id);
```

**Cel:**
- Przyspieszenie zapytań
- Optymalizacja wyszukiwania

**Korzyści:**
- Szybsze pobieranie danych
- Mniejsze obciążenie serwera

---

## Podsumowanie

| Funkcja | Typ | Zastosowanie | Korzyść |
|---------|-----|--------------|---------|
| `sales_report` | View | Raporty sprzedaży | Szybkie statystyki |
| `product_stats` | View | Analiza produktów | Kompleksowe dane |
| `top_products` | View | Bestsellery | Rekomendacje |
| `user_order_history` | View | Historia zamówień | Szybki dostęp |
| `update_stock_on_order_insert` | Trigger | Zmniejszenie stanu | Automatyzacja |
| `restore_stock_on_order_delete` | Trigger | Przywrócenie stanu | Spójność danych |
| `restore_stock_on_order_cancel` | Trigger | Anulowanie zamówienia | Bezpieczeństwo |
| `calculate_order_total()` | Function | Obliczenie wartości | Konsystencja |
| `is_product_available()` | Function | Sprawdzenie dostępności | Walidacja |
| Transakcje | Transaction | Proces zamawiania | Atomowość |

---

**Ostatnia aktualizacja:** 30 października 2025
