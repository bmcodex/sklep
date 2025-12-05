<?php
// Skrypt do aktualizacji zdjęć produktów w bazie danych
// Uruchom przez przeglądarkę: https://bmcodex.pl/update_images_now.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h1>Aktualizacja zdjęć produktów</h1>";
echo "<pre>";

try {
    // Aktualizacja zdjęć według ID
    $updates = [
        1 => '/images/products/produkt1.jpg',
        2 => '/images/products/produkt2.jpg',
        3 => '/images/products/produkt3.jpg',
        4 => '/images/products/produkt4.jpg',
        5 => '/images/products/produkt5.jpg',
        6 => '/images/products/produkt6.jpg',
    ];
    
    foreach ($updates as $id => $imageUrl) {
        $result = DB::table('products')
            ->where('id', $id)
            ->update(['image_url' => $imageUrl]);
        
        if ($result) {
            echo "✓ Produkt ID {$id} zaktualizowany: {$imageUrl}\n";
        } else {
            echo "✗ Nie znaleziono produktu ID {$id}\n";
        }
    }
    
    echo "\n✅ Aktualizacja zakończona pomyślnie!\n";
    
} catch (Exception $e) {
    echo "❌ Błąd: " . $e->getMessage() . "\n";
}

echo "</pre>";
echo "<p><a href='/'>Powrót do strony głównej</a></p>";
?>
