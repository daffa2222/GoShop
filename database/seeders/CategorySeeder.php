<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Elektronik']);
        Category::create(['name' => 'Fashion Pria']);
        Category::create(['name' => 'Buku']);
        Category::create(['name' => 'Kesehatan']);
        Category::create(['name' => 'Olahraga']);
        Category::create(['name' => 'Kecantikan']);
    }
}
