<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Produk elektronik dan gadget terbaru'],
            ['name' => 'Fashion', 'description' => 'Pakaian, sepatu, dan aksesoris fashion'],
            ['name' => 'Buku', 'description' => 'Buku dari berbagai genre dan penulis'],
            ['name' => 'Olahraga', 'description' => 'Peralatan olahraga dan fitness'],
            ['name' => 'Kecantikan', 'description' => 'Produk perawatan dan kecantikan'],
            ['name' => 'Furniture', 'description' => 'Perabotan rumah tangga'],
            ['name' => 'Mainan', 'description' => 'Mainan anak dan hobi'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']), // TAMBAHKAN INI
                'description' => $category['description'],
            ]);
        }
    }
}