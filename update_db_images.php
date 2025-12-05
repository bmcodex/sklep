<?php
// Aktualizacja ścieżek do zdjęć w bazie danych

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "Aktualizacja ścieżek do zdjęć produktów...\n\n";

$updates = [
    'BMC-EXH-001' => '/images/products/wydech_titan.jpg',
    'BMC-INT-001' => '/images/products/intercooler_frostbite.jpg',
    'BMC-SUS-001' => '/images/products/zawieszenie_trackmaster.jpg',
    'BMC-EXH-002' => '/images/products/downpipe.jpg',
    'BMC-ECU-001' => '/images/products/ecu_powerboost.jpg',
    'BMC-AIR-001' => '/images/products/air_filter_airflow.jpg',
];

foreach ($updates as $sku => $imageUrl) {
    $product = Product::where('sku', $sku)->first();
    
    if ($product) {
        $oldUrl = $product->image_url;
        $product->image_url = $imageUrl;
        $product->save();
        
        echo "✓ {$product->name}\n";
        echo "  Stary: {$oldUrl}\n";
        echo "  Nowy: {$imageUrl}\n\n";
    } else {
        echo "✗ Nie znaleziono produktu: {$sku}\n\n";
    }
}

echo "Aktualizacja zakończona!\n";
?>
