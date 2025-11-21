<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Utama
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Seller Approved (siap punya toko & produk)
        User::create([
            'name' => 'Seller A (Approved)',
            'email' => 'seller.a@mail.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        // 3. Seller Pending (untuk testing menu verifikasi admin)
        User::create([
            'name' => 'Seller B (Pending)',
            'email' => 'seller.b@mail.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'seller_status' => 'pending',
        ]);

        // 4. Buyer Aktif (untuk testing Cart, Checkout, Order, Rating)
        User::create([
            'name' => 'Buyer C',
            'email' => 'buyer.c@mail.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);
    }
}
