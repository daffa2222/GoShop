<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Store;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = Store::first(); // Ambil Toko Seller A
        $elektronikCategory = Category::where('name', 'Elektronik')->first();

        if ($store && $elektronikCategory) {
            
            // Produk 1: Laptop ASUS ROG Gaming
            Product::create([
                'store_id' => $store->id,
                'category_id' => $elektronikCategory->id,
                'name' => 'Laptop ASUS ROG Gaming',
                'description' => 'Laptop gaming dengan processor Intel Core i7 Gen 12, RAM 16GB, SSD 512GB, VGA RTX 3060. Cocok untuk gaming dan editing video.',
                'price' => 15000000,
                'stock' => 10,
                'images' => ['laptop-rog.jpg', 'laptop-rog-detail.jpg'], // KOREKSI: Gunakan 'images' dan nilainya array
            ]);

            // Produk 2: Mouse Wireless Logitech MX Master 3
            Product::create([
                'store_id' => $store->id,
                'category_id' => $elektronikCategory->id,
                'name' => 'Mouse Wireless Logitech MX Master 3',
                'description' => 'Mouse wireless ergonomis dengan sensor presisi tinggi. Baterai tahan hingga 70 hari. Cocok untuk profesional.',
                'price' => 1250000,
                'stock' => 50,
                'images' => ['mouse-logitech.jpg'], // KOREKSI
            ]);

            // Produk 3: Keyboard Mechanical RGB Keychron K2
            Product::create([
                'store_id' => $store->id,
                'category_id' => $elektronikCategory->id,
                'name' => 'Keyboard Mechanical RGB Keychron K2',
                'description' => 'Keyboard mechanical dengan switch Gateron, lampu RGB customizable, koneksi wireless dan kabel. Desain compact 75%.',
                'price' => 1500000,
                'stock' => 25,
                'images' => ['keyboard-keychron.jpg'], // KOREKSI
            ]);
            
            // Produk 4: Monitor LG 27 inch 144Hz Gaming
            Product::create([
                'store_id' => $store->id,
                'category_id' => $elektronikCategory->id,
                'name' => 'Monitor LG 27 inch 144Hz Gaming',
                'description' => 'Monitor gaming IPS 27 inch dengan refresh rate 144Hz, response time 1ms, HDR10. Perfect untuk gaming kompetitif.',
                'price' => 3500000,
                'stock' => 15,
                'images' => ['monitor-lg.jpg'], // KOREKSI
            ]);

            // Produk 5: Webcam Logitech C920 HD Pro
            Product::create([
                'store_id' => $store->id,
                'category_id' => $elektronikCategory->id,
                'name' => 'Webcam Logitech C920 HD Pro',
                'description' => 'Webcam Full HD 1080p dengan auto-focus dan koreksi cahaya otomatis. Ideal untuk meeting online dan streaming.',
                'price' => 1200000,
                'stock' => 30,
                'images' => ['webcam-logitech.jpg'], // KOREKSI
            ]);

            // Produk 6: Headset Gaming HyperX Cloud II
            Product::create([
                'store_id' => $store->id,
                'category_id' => $elektronikCategory->id,
                'name' => 'Headset Gaming HyperX Cloud II',
                'description' => 'Headset gaming dengan 7.1 surround sound, mic noise-cancelling, dan bantalan memory foam yang nyaman untuk gaming marathon.',
                'price' => 1100000,
                'stock' => 40,
                'images' => ['headset-hyperx.jpg'], // KOREKSI
            ]);

            // Produk 7: SSD Samsung 970 EVO Plus 1TB
            Product::create([
                'store_id' => $store->id,
                'category_id' => $elektronikCategory->id,
                'name' => 'SSD Samsung 970 EVO Plus 1TB',
                'description' => 'SSD NVMe M.2 dengan kecepatan baca 3500MB/s dan tulis 3300MB/s. Upgrade storage terbaik untuk laptop dan PC.',
                'price' => 1800000,
                'stock' => 20,
                'images' => ['ssd-samsung.jpg'], // KOREKSI
            ]);

            // Produk 8: Power Bank Anker 20000mAh
            Product::create([
                'store_id' => $store->id,
                'category_id' => $elektronikCategory->id,
                'name' => 'Power Bank Anker 20000mAh',
                'description' => 'Power bank berkapasitas besar dengan fast charging 18W, 2 port USB output. Dapat mengisi smartphone hingga 5x.',
                'price' => 450000,
                'stock' => 60,
                'images' => ['powerbank-anker.jpg'], // KOREKSI
            ]);
        }
    }
}