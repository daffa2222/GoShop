<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $buyerC = User::where('email', 'buyer.c@mail.com')->first();
        $product1 = Product::first();

        if ($buyerC && $product1) {

            // 1. Order Selesai (bisa direview Buyer)
            $orderSelesai = Order::create([
                'user_id' => $buyerC->id,
                'status' => 'Selesai',
                'total_price' => $product1->price,
            ]);

            OrderItem::create([
                'order_id' => $orderSelesai->id,
                'product_id' => $product1->id,
                'quantity' => 1,
                'price' => $product1->price,
            ]);

            // 2. Order Diproses (untuk testing fitur Seller update status)
            $orderDiproses = Order::create([
                'user_id' => $buyerC->id,
                'status' => 'Diproses',
                'total_price' => $product1->price * 2,
            ]);

            OrderItem::create([
                'order_id' => $orderDiproses->id,
                'product_id' => $product1->id,
                'quantity' => 2,
                'price' => $product1->price,
            ]);
        }
    }
}
