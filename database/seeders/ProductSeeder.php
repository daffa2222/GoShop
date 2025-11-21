<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Store;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil Toko Seller A
        $store = Store::first();

        // Ambil Kategori Elektronik
        $categoryElektronik = Category::where('name', 'Elektronik')->first();

        if ($store && $categoryElektronik) {
            
            Product::create([
                'store_id' => $store->id,
                'category_id' => $categoryElektronik->id,
                'name' => 'Smartphone Keren 5G',
                'description' => 'HP dengan fitur canggih dan harga terjangkau.',
                'price' => 3500000,
                'stock' => 20,
            ]);

            Product::create([
                'store_id' => $store->id,
                'category_id' => $categoryElektronik->id,
                'name' => 'Smartwatch Ultra',
                'description' => 'Jam tangan pintar untuk gaya hidup sehat.',
                'price' => 1200000,
                'stock' => 50,
            ]);
        }
    }
}
