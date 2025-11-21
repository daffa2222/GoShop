<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'approved',
        ]);

        // Seller (Approved)
        User::create([
            'name' => 'Toko Elektronik Jaya',
            'email' => 'penjual1@gmail.com',
            'password' => Hash::make('penjual123'),
            'role' => 'seller',
            'status' => 'approved',
        ]);

        // Seller (Pending)
        User::create([
            'name' => 'Toko Fashion Keren',
            'email' => 'penjual2@gmail.com',
            'password' => Hash::make('penjual123'),
            'role' => 'seller',
            'status' => 'pending',
        ]);

        // Buyers
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('pembeli123'),
            'role' => 'buyer',
            'status' => 'approved',
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('pembeli123'),
            'role' => 'buyer',
            'status' => 'approved',
        ]);
    }
}