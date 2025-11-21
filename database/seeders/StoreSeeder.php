<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID Seller A yang statusnya 'approved'
        $sellerA = User::where('email', 'seller.a@mail.com')
            ->where('seller_status', 'approved')
            ->first();

        if ($sellerA) {
            Store::create([
                'user_id' => $sellerA->id,
                'name' => 'Gadget Store Seller A',
                'description' => 'Toko khusus barang-barang elektronik.',
            ]);
        }
    }
}
