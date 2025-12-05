# BMCODEX - Project TODO

## Phase 1: Database & Backend Setup
- [ ] Migracje bazy danych (users, categories, products, orders, order_items, favorites, cart_items)
- [ ] Seedery z przykładowymi danymi (kategorie, produkty, użytkownicy)
- [ ] Modele Eloquent (User, Product, Category, Order, OrderItem, Favorite)
- [ ] Widoki SQL (raport sprzedaży, statystyki)
- [ ] Wyzwalacze SQL (aktualizacja stanu magazynowego)
- [ ] Transakcje dla procesów krytycznych

## Phase 2: Authentication & Authorization
- [ ] Logowanie/rejestracja użytkownika
- [ ] Reset hasła (email z linkiem resetującym)
- [ ] Potwierdzenie email podczas rejestracji
- [ ] Role-based access control (user, employee, admin)
- [ ] Middleware do ochrony tras

## Phase 3: Frontend - Home & Product Catalog
- [ ] Strona główna z listą produktów
- [ ] Filtrowanie produktów (kategoria, cena, nazwa)
- [ ] Widok szczegółowy produktu
- [ ] Responsywny design z kolorami BMCODEX (#FF4500, #1A1A1A)
- [ ] Nawigacja główna

## Phase 4: Shopping Cart & Checkout
- [ ] Logika koszyka (dodawanie, usuwanie, zmiana ilości)
- [ ] Strona koszyka
- [ ] Proces zamawiania (adres, podsumowanie)
- [ ] Obsługa zakupów jako gość
- [ ] Obsługa zakupów zalogowanego użytkownika

## Phase 5: Customer Dashboard & Admin Panel
- [ ] Panel klienta (historia zamówień, dane profilu, edycja)
- [ ] Ulubione produkty / Przechowalna
- [ ] Panel administracyjny (CRUD produkty, zamówienia, użytkownicy)
- [ ] Zarządzanie pracownikami (dla admina)
- [ ] Widoki dla różnych ról (user, employee, admin)

## Phase 6: Email & Notifications
- [ ] Potwierdzenie zamówienia mailem
- [ ] Powiadomienia o statusie zamówienia
- [ ] Email z resetem hasła
- [ ] Email z potwierdzeniem rejestracji

## Phase 7: Optional Features
- [ ] Integracja PayU (testowanie w Sandbox)
- [ ] Recenzje produktów
- [ ] Rekomendacje produktów

## Phase 8: Documentation & Deployment
- [ ] Dokumentacja techniczna (architektura, baza danych)
- [ ] Dokumentacja użytkownika (userGuide.md)
- [ ] Plik opisujący użyte widoki, wyzwalacze, funkcje SQL
- [ ] Wdrożenie na serwerze
- [ ] Konfiguracja PhpMyAdmin
- [ ] Repozytorium GitHub

## Phase 9: Testing & Final Delivery
- [ ] Testy funkcjonalne
- [ ] Testy bezpieczeństwa
- [ ] Przygotowanie danych dostępowych (login, hasło)
- [ ] Finalne sprawdzenie wszystkich wymagań
