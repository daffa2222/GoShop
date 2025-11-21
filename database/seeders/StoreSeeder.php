<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run()
    {
        // Ambil seller yang approved
        $seller = User::where('role', 'seller')
                      ->where('status', 'approved')
                      ->first();

        if ($seller) {
            Store::create([
                'user_id'    => $seller->id,
                'name'       => 'Toko Elektronik Jaya',
                'slug'       => Str::slug('Toko Elektronik Jaya'),
                'description'=> 'Menyediakan berbagai produk elektronik berkualitas dengan harga terjangkau. Terpercaya sejak 2020.',
                'logo'       => 'default-logo.png',
                'banner'     => 'default-banner.jpg',
            ]);
        }
    }
}
