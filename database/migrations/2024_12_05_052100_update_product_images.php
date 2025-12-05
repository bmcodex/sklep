<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Aktualizacja ścieżek do zdjęć produktów
        DB::table('products')->where('sku', 'BMC-EXH-001')->update(['image_url' => '/images/products/wydech_titan.jpg']);
        DB::table('products')->where('sku', 'BMC-INT-001')->update(['image_url' => '/images/products/intercooler_frostbite.jpg']);
        DB::table('products')->where('sku', 'BMC-SUS-001')->update(['image_url' => '/images/products/zawieszenie_trackmaster.jpg']);
        DB::table('products')->where('sku', 'BMC-EXH-002')->update(['image_url' => '/images/products/downpipe.jpg']);
        DB::table('products')->where('sku', 'BMC-ECU-001')->update(['image_url' => '/images/products/ecu_powerboost.jpg']);
        DB::table('products')->where('sku', 'BMC-AIR-001')->update(['image_url' => '/images/products/air_filter_airflow.jpg']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Przywrócenie starych ścieżek
        DB::table('products')->where('sku', 'BMC-EXH-001')->update(['image_url' => '/images/products/exhaust_titan.jpg']);
        DB::table('products')->where('sku', 'BMC-INT-001')->update(['image_url' => '/images/products/intercooler_frostbite.jpg']);
        DB::table('products')->where('sku', 'BMC-SUS-001')->update(['image_url' => '/images/products/ecu_powerboost.jpg']);
        DB::table('products')->where('sku', 'BMC-EXH-002')->update(['image_url' => '/images/products/coilovers_trackmaster.jpg']);
        DB::table('products')->where('sku', 'BMC-ECU-001')->update(['image_url' => '/images/products/installation_kit.jpg']);
        DB::table('products')->where('sku', 'BMC-AIR-001')->update(['image_url' => '/images/products/air_filter_airflow.jpg']);
    }
};
