-- Aktualizacja zdjęć produktów według ID
-- Wykonaj ten plik w phpMyAdmin lub przez mysql CLI

UPDATE products SET image_url = '/images/products/produkt1.jpg' WHERE id = 1;
UPDATE products SET image_url = '/images/products/produkt2.jpg' WHERE id = 2;
UPDATE products SET image_url = '/images/products/produkt3.jpg' WHERE id = 3;
UPDATE products SET image_url = '/images/products/produkt4.jpg' WHERE id = 4;
UPDATE products SET image_url = '/images/products/produkt5.jpg' WHERE id = 5;
UPDATE products SET image_url = '/images/products/produkt6.jpg' WHERE id = 6;

-- Weryfikacja zmian
SELECT id, name, image_url FROM products ORDER BY id;
