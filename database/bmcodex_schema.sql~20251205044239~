-- =====================================================
-- BMCODEX - Performance Without Limits
-- Pełny skrypt tworzący bazę danych MySQL 8+
-- =====================================================

-- Tworzenie bazy danych
CREATE DATABASE IF NOT EXISTS bmcodex_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bmcodex_db;

-- =====================================================
-- TABELE PODSTAWOWE
-- =====================================================

-- Tabela: Użytkownicy (klienci, pracownicy, admini)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('user', 'employee', 'admin') NOT NULL DEFAULT 'user',
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    reset_token VARCHAR(255) NULL,
    reset_token_expires_at DATETIME NULL,
    email_verified_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Kategorie produktów
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Produkty
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    sku VARCHAR(100) UNIQUE,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_sku (sku),
    INDEX idx_price (price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Zamówienia
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    guest_email VARCHAR(255) NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    billing_address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Pozycje zamówienia
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_per_item DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Ulubione produkty
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_product (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Koszyk
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_id VARCHAR(255),
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_session (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- WIDOKI SQL (VIEWS)
-- =====================================================

-- Widok: Raport sprzedaży
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

-- Widok: Statystyki produktów
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

-- Widok: Top produkty
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

-- Widok: Historia zamówień użytkownika
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

-- =====================================================
-- WYZWALACZE (TRIGGERS)
-- =====================================================

-- Wyzwalacz: Aktualizacja stanu magazynowego po dodaniu pozycji do zamówienia
DELIMITER $$

CREATE TRIGGER update_stock_on_order_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products 
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;
END$$

DELIMITER ;

-- Wyzwalacz: Przywrócenie stanu magazynowego po usunięciu pozycji z zamówienia
DELIMITER $$

CREATE TRIGGER restore_stock_on_order_delete
AFTER DELETE ON order_items
FOR EACH ROW
BEGIN
    UPDATE products 
    SET stock = stock + OLD.quantity
    WHERE id = OLD.product_id;
END$$

DELIMITER ;

-- Wyzwalacz: Przywrócenie stanu magazynowego po anulowaniu zamówienia
DELIMITER $$

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
END$$

DELIMITER ;

-- =====================================================
-- DANE PRZYKŁADOWE
-- =====================================================

-- Kategorie
INSERT INTO categories (name, description) VALUES
('Układy wydechowe', 'Sportowe układy wydechowe, downpipe, cat-backi.'),
('Układy dolotowe', 'Wydajne filtry powietrza, intercoolery, doloty typu cold air intake.'),
('Zawieszenia', 'Gwintowane zawieszenia, sportowe sprężyny, stabilizatory.'),
('Elektronika', 'Moduły sterujące silnikiem (ECU), piggybacki, wskaźniki.');

-- Użytkownicy
INSERT INTO users (role, first_name, last_name, email, password) VALUES
('admin', 'Michał', 'Nurzyński', 'admin@bmcodex.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('user', 'Jan', 'Kowalski', 'jan.kowalski@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('user', 'Anna', 'Nowak', 'anna.nowak@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Produkty
INSERT INTO products (category_id, name, description, price, stock, sku, image_url) VALUES
(1, 'Wydech sportowy "Titan"', 'Kompletny układ wydechowy cat-back ze stali nierdzewnej T304.', 2499.99, 15, 'BMC-EXH-001', '/images/wydech_titan.jpg'),
(2, 'Intercooler "FrostBite"', 'Wydajny intercooler czołowy, obniża temperaturę powietrza dolotowego o 20%.', 1850.00, 10, 'BMC-INT-001', '/images/intercooler_frostbite.jpg'),
(3, 'Zawieszenie gwintowane "TrackMaster"', 'Pełna regulacja wysokości i twardości. Idealne na tor i na co dzień.', 4200.50, 8, 'BMC-SUS-001', '/images/zawieszenie_trackmaster.jpg'),
(1, 'Downpipe 3" bez katalizatora', 'Zwiększa przepływ spalin, poprawiając reakcję na gaz i moc.', 899.00, 25, 'BMC-EXH-002', '/images/downpipe.jpg'),
(4, 'Moduł ECU "PowerBoost"', 'Zwiększa moc silnika o 30 KM. Kompatybilny z popularnymi modelami BMW.', 3499.99, 5, 'BMC-ECU-001', '/images/ecu_powerboost.jpg'),
(2, 'Filtr powietrza sportowy "AirMax"', 'Wielowarstwowy filtr o zwiększonej przepustowości.', 299.99, 50, 'BMC-AIR-001', '/images/filtr_airmax.jpg');

-- Przykładowe zamówienie
INSERT INTO orders (user_id, total_price, status, shipping_address, billing_address) VALUES
(2, 3349.99, 'delivered', 'ul. Przykładowa 1, 00-001 Warszawa', 'ul. Przykładowa 1, 00-001 Warszawa');

-- Pozycje zamówienia
INSERT INTO order_items (order_id, product_id, quantity, price_per_item) VALUES
(1, 1, 1, 2499.99),
(1, 6, 1, 849.99);

-- =====================================================
-- FUNKCJE POMOCNICZE
-- =====================================================

-- Funkcja: Obliczanie całkowitej wartości zamówienia
DELIMITER $$

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
END$$

DELIMITER ;

-- Funkcja: Sprawdzanie dostępności produktu
DELIMITER $$

CREATE FUNCTION is_product_available(product_id INT, required_quantity INT)
RETURNS BOOLEAN
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE available INT;
    SELECT stock INTO available FROM products WHERE id = product_id;
    RETURN IFNULL(available >= required_quantity, FALSE);
END$$

DELIMITER ;

-- =====================================================
-- INDEKSY DLA OPTYMALIZACJI
-- =====================================================

-- Indeksy dla szybszych wyszukiwań
CREATE INDEX idx_order_user_date ON orders(user_id, created_at);
CREATE INDEX idx_product_category_price ON products(category_id, price);
CREATE INDEX idx_order_items_order_product ON order_items(order_id, product_id);

-- =====================================================
-- KONIEC SKRYPTU
-- =====================================================
