# Dokumentacja Widoków i Funkcji Bazy Danych - Sklep BMCodex

## 1. Widoki (Views)

Widoki w bazie danych są używane do uproszczenia skomplikowanych zapytań, zapewnienia bezpieczeństwa danych oraz prezentacji danych w bardziej czytelnej formie. W projekcie sklepu BMCodex, widoki mogą być używane do:

- **Agregacji danych:** Tworzenie podsumowań sprzedaży, popularności produktów, itp.
- **Uproszczenia zapytań:** Łączenie wielu tabel w jeden wirtualny widok.
- **Bezpieczeństwa:** Ograniczenie dostępu do wrażliwych danych dla określonych użytkowników.

### Przykładowe widoki, które można zaimplementować:

#### `v_product_details`

Ten widok łączy informacje o produktach z ich kategoriami, co ułatwia wyświetlanie szczegółów produktów w sklepie.

**Struktura widoku:**

| Kolumna | Typ danych | Opis |
|---|---|---|
| `product_id` | INT | ID produktu |
| `product_name` | VARCHAR | Nazwa produktu |
| `product_description` | TEXT | Opis produktu |
| `product_price` | DECIMAL | Cena produktu |
| `product_stock` | INT | Stan magazynowy |
| `category_name` | VARCHAR | Nazwa kategorii |

**Kod SQL do stworzenia widoku:**

```sql
CREATE VIEW v_product_details AS
SELECT 
    p.id AS product_id,
    p.name AS product_name,
    p.description AS product_description,
    p.price AS product_price,
    p.stock AS product_stock,
    c.name AS category_name
FROM 
    products p
JOIN 
    categories c ON p.category_id = c.id;
```

**Zastosowanie w sklepie:**

- **Strona główna:** Wyświetlanie listy produktów z nazwami kategorii.
- **Strona produktu:** Prezentacja szczegółowych informacji o produkcie.
- **Panel administracyjny:** Zarządzanie produktami z podglądem kategorii.

#### `v_order_summary`

Ten widok agreguje dane o zamówieniach, aby ułatwić analizę sprzedaży i generowanie raportów.

**Struktura widoku:**

| Kolumna | Typ danych | Opis |
|---|---|---|
| `order_id` | INT | ID zamówienia |
| `customer_name` | VARCHAR | Imię i nazwisko klienta |
| `order_date` | TIMESTAMP | Data zamówienia |
| `total_amount` | DECIMAL | Całkowita kwota zamówienia |
| `order_status` | VARCHAR | Status zamówienia |

**Kod SQL do stworzenia widoku:**

```sql
CREATE VIEW v_order_summary AS
SELECT
    o.id AS order_id,
    CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
    o.created_at AS order_date,
    o.total_amount,
    o.status AS order_status
FROM
    orders o
JOIN
    users u ON o.user_id = u.id;
```

**Zastosowanie w sklepie:**

- **Panel administracyjny:** Wyświetlanie listy zamówień z podsumowaniem.
- **Raporty sprzedaży:** Generowanie raportów dziennych, miesięcznych, itp.
- **Panel klienta:** Wyświetlanie historii zamówień klienta.

---

## 2. Wyzwalacze (Triggers)

Wyzwalacze to specjalne procedury składowane, które są automatycznie wykonywane w odpowiedzi na określone zdarzenia w bazie danych (np. `INSERT`, `UPDATE`, `DELETE`). W sklepie BMCodex, wyzwalacze mogą być używane do:

- **Automatyzacji procesów:** Aktualizacja stanu magazynowego po złożeniu zamówienia.
- **Logowania zmian:** Zapisywanie historii zmian w ważnych tabelach.
- **Walidacji danych:** Sprawdzanie poprawności danych przed ich zapisaniem.

### Przykładowe wyzwalacze, które można zaimplementować:

#### `trg_update_stock_after_order`

Ten wyzwalacz automatycznie aktualizuje stan magazynowy produktu po dodaniu nowego zamówienia.

**Kod SQL do stworzenia wyzwalacza:**

```sql
CREATE TRIGGER trg_update_stock_after_order
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;
END;
```

**Zastosowanie w sklepie:**

- **Automatyczna aktualizacja stanu magazynowego:** Zapewnia, że stan magazynowy jest zawsze aktualny.
- **Zapobieganie sprzedaży niedostępnych produktów:** Zmniejsza ryzyko sprzedaży produktów, których nie ma na stanie.

#### `trg_log_price_changes`

Ten wyzwalacz zapisuje historię zmian cen produktów w osobnej tabeli `price_history`.

**Kod SQL do stworzenia wyzwalacza:**

```sql
CREATE TRIGGER trg_log_price_changes
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
    IF OLD.price <> NEW.price THEN
        INSERT INTO price_history (product_id, old_price, new_price, change_date)
        VALUES (NEW.id, OLD.price, NEW.price, NOW());
    END IF;
END;
```

**Zastosowanie w sklepie:**

- **Śledzenie historii cen:** Umożliwia analizę zmian cen w czasie.
- **Audyt:** Zapewnia ślad rewizyjny dla zmian cen produktów.

---

## 3. Inne funkcje usprawniające działanie sklepu

Oprócz widoków i wyzwalaczy, w projekcie sklepu BMCodex można zaimplementować inne funkcje bazy danych, które usprawnią jego działanie:

### Procedury składowane (Stored Procedures)

Procedury składowane to prekompilowane zestawy zapytań SQL, które mogą być wywoływane z aplikacji. Użycie procedur składowanych może:

- **Zwiększyć wydajność:** Zapytania są kompilowane tylko raz.
- **Zmniejszyć ruch sieciowy:** Aplikacja wysyła tylko nazwę procedury i parametry.
- **Zwiększyć bezpieczeństwo:** Można nadawać uprawnienia do wykonania procedury, a nie do tabel.

**Przykład procedury do dodawania produktu do koszyka:**

```sql
CREATE PROCEDURE sp_add_to_cart(IN user_id INT, IN product_id INT, IN quantity INT)
BEGIN
    -- Logika dodawania produktu do koszyka
END;
```

### Funkcje zdefiniowane przez użytkownika (User-Defined Functions)

Funkcje UDF pozwalają na tworzenie własnych funkcji, które mogą być używane w zapytaniach SQL. Mogą one uprościć skomplikowane obliczenia i logikę biznesową.

**Przykład funkcji do obliczania ceny z rabatem:**

```sql
CREATE FUNCTION fn_calculate_discounted_price(price DECIMAL(10, 2), discount_percentage INT)
RETURNS DECIMAL(10, 2)
BEGIN
    RETURN price - (price * discount_percentage / 100);
END;
```

### Indeksy (Indexes)

Indeksy są kluczowe dla wydajności zapytań w bazie danych. W sklepie BMCodex, indeksy powinny być założone na kolumnach, które są często używane w klauzulach `WHERE`, `JOIN` i `ORDER BY`.

**Przykładowe kolumny do zindeksowania:**

- `products.name`
- `products.category_id`
- `orders.user_id`
- `orders.created_at`

**Kod SQL do stworzenia indeksu:**

```sql
CREATE INDEX idx_products_name ON products(name);
```

---

## Podsumowanie

Wykorzystanie widoków, wyzwalaczy, procedur składowanych, funkcji UDF i indeksów w bazie danych sklepu BMCodex może znacznie poprawić jego wydajność, bezpieczeństwo i łatwość zarządzania. Implementacja tych funkcji powinna być starannie zaplanowana i dostosowana do specyficznych potrzeb projektu.
