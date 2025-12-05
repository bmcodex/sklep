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
        // Przypisanie zdjęć do produktów według ID
        DB::table('products')->where('id', 1)->update(['image_url' => '/images/products/produkt1.jpg']);
        DB::table('products')->where('id', 2)->update(['image_url' => '/images/products/produkt2.jpg']);
        DB::table('products')->where('id', 3)->update(['image_url' => '/images/products/produkt3.jpg']);
        DB::table('products')->where('id', 4)->update(['image_url' => '/images/products/produkt4.jpg']);
        DB::table('products')->where('id', 5)->update(['image_url' => '/images/products/produkt5.jpg']);
        DB::table('products')->where('id', 6)->update(['image_url' => '/images/products/produkt6.jpg']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Przywrócenie poprzednich ścieżek
        DB::table('products')->where('id', 1)->update(['image_url' => '/images/products/wydech_titan.jpg']);
        DB::table('products')->where('id', 2)->update(['image_url' => '/images/products/intercooler_frostbite.jpg']);
        DB::table('products')->where('id', 3)->update(['image_url' => '/images/products/zawieszenie_trackmaster.jpg']);
        DB::table('products')->where('id', 4)->update(['image_url' => '/images/products/downpipe.jpg']);
        DB::table('products')->where('id', 5)->update(['image_url' => '/images/products/ecu_powerboost.jpg']);
        DB::table('products')->where('id', 6)->update(['image_url' => '/images/products/air_filter_airflow.jpg']);
    }
};
