<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Układy wydechowe',
            'description' => 'Sportowe układy wydechowe, downpipe, cat-backi.',
        ]);

        Category::create([
            'name' => 'Układy dolotowe',
            'description' => 'Wydajne filtry powietrza, intercoolery, doloty typu cold air intake.',
        ]);

        Category::create([
            'name' => 'Zawieszenia',
            'description' => 'Gwintowane zawieszenia, sportowe sprężyny, stabilizatory.',
        ]);

        Category::create([
            'name' => 'Elektronika',
            'description' => 'Moduły sterujące silnikiem (ECU), piggybacki, wskaźniki.',
        ]);
    }
}
