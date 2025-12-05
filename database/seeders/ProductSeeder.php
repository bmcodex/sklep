<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_id' => 1,
            'name' => 'Wydech sportowy "Titan"',
            'description' => 'Kompletny układ wydechowy cat-back ze stali nierdzewnej T304.',
            'price' => 2499.99,
            'stock' => 15,
            'sku' => 'BMC-EXH-001',
            'image_url' => '/images/wydech_titan.jpg',
        ]);

        Product::create([
            'category_id' => 2,
            'name' => 'Intercooler "FrostBite"',
            'description' => 'Wydajny intercooler czołowy, ob niża temperaturę powietrza dolotowego o 20%.',
            'price' => 1850.00,
            'stock' => 10,
            'sku' => 'BMC-INT-001',
            'image_url' => '/images/intercooler_frostbite.jpg',
        ]);

        Product::create([
            'category_id' => 3,
            'name' => 'Zawieszenie gwintowane "TrackMaster"',
            'description' => 'Pełna regulacja wysokości i twardości. Idealne na tor i na co dzień.',
            'price' => 4200.50,
            'stock' => 8,
            'sku' => 'BMC-SUS-001',
            'image_url' => '/images/zawieszenie_trackmaster.jpg',
        ]);

        Product::create([
            'category_id' => 1,
            'name' => 'Downpipe 3" bez katalizatora',
            'description' => 'Zwiększa przepływ spalin, poprawiając reakcję na gaz i moc.',
            'price' => 899.00,
            'stock' => 25,
            'sku' => 'BMC-EXH-002',
            'image_url' => '/images/downpipe.jpg',
        ]);

        Product::create([
            'category_id' => 4,
            'name' => 'Moduł ECU "PowerBoost"',
            'description' => 'Zwiększa moc silnika o 30 KM. Kompatybilny z popularnymi modelami BMW.',
            'price' => 3499.99,
            'stock' => 5,
            'sku' => 'BMC-ECU-001',
            'image_url' => '/images/ecu_powerboost.jpg',
        ]);

        Product::create([
            'category_id' => 2,
            'name' => 'Filtr powietrza sportowy "AirMax"',
            'description' => 'Wielowarstwowy filtr o zwiększonej przepustowości.',
            'price' => 299.99,
            'stock' => 50,
            'sku' => 'BMC-AIR-001',
            'image_url' => '/images/filtr_airmax.jpg',
        ]);
    }
}
