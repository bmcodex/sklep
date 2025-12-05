<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'role' => 'admin',
            'first_name' => 'Michał',
            'last_name' => 'Nurzyński',
            'email' => 'admin@bmcodex.com',
            'password' => Hash::make('password123'),
            'phone' => '+48123456789',
            'email_verified_at' => now(),
        ]);

        User::create([
            'role' => 'user',
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'email' => 'jan.kowalski@example.com',
            'password' => Hash::make('password123'),
            'phone' => '+48987654321',
            'email_verified_at' => now(),
        ]);

        User::create([
            'role' => 'user',
            'first_name' => 'Anna',
            'last_name' => 'Nowak',
            'email' => 'anna.nowak@example.com',
            'password' => Hash::make('password123'),
            'phone' => '+48555555555',
            'email_verified_at' => now(),
        ]);
    }
}
