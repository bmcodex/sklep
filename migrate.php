<?php
// Skrypt do uruchomienia migracji Laravel bez Artisan

define('LARAVEL_START', microtime(true));

// Załaduj autoloader
require __DIR__.'/vendor/autoload.php';

// Załaduj aplikację Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Uruchom kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Uruchom migracje
$status = $kernel->call('migrate', [
    '--force' => true,
]);

echo "Migracje wykonane! Status: " . $status . "\n";

// Uruchom seedery
$status = $kernel->call('db:seed', [
    '--force' => true,
]);

echo "Seedery wykonane! Status: " . $status . "\n";

// Wyczyść cache
$kernel->call('config:clear');
$kernel->call('cache:clear');
$kernel->call('route:clear');
$kernel->call('view:clear');

echo "Cache wyczyszczony!\n";
echo "Wdrożenie zakończone pomyślnie!\n";
