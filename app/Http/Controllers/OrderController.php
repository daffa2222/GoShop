<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // BUYER: Menampilkan riwayat pesanan
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                    ->with('orderItems.product')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    // BUYER: Checkout keranjang menjadi order
    public function checkout(Request $request)
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();

        if ($carts->isEmpty()) {
            return redirect()->route('buyer.cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        // Validasi stok sebelum checkout
        foreach ($carts as $cart) {
            if ($cart->product->stock < $cart->quantity) {
                return back()->with('error', 'Stok produk ' . $cart->product->name . ' tidak mencukupi!');
            }
        }

        // Hitung total
        $totalPrice = $carts->sum(function($cart) {
            return $cart->quantity * $cart->product->price;
        });

        DB::beginTransaction();
        try {
            // Buat order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'status' => 'Menunggu Pembayaran'
            ]);

            // Buat order items dan kurangi stok
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price
                ]);

                // Kurangi stok produk
                $cart->product->decrement('stock', $cart->quantity);
            }

            // Hapus semua item di cart setelah checkout
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('buyer.orders.index')->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage());
        }
    }

    // BUYER: Menampilkan detail pesanan
    public function show(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $order->load('orderItems.product');

        return view('buyer.orders.show', compact('order'));
    }

    // SELLER: Menampilkan pesanan masuk ke toko
    public function sellerOrders()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->route('seller.dashboard')->with('error', 'Anda belum memiliki toko.');
        }

        // Ambil semua order yang mengandung produk dari toko seller
        $orders = Order::whereHas('orderItems.product', function($query) use ($store) {
                    $query->where('store_id', $store->id);
                })
                ->with(['orderItems' => function($query) use ($store) {
                    $query->whereHas('product', function($q) use ($store) {
                        $q->where('store_id', $store->id);
                    });
                }, 'orderItems.product', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    // SELLER: Update status pesanan
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:Menunggu Pembayaran,Diproses,Dikirim,Selesai,Dibatalkan'
        ]);

        $order->update($validated);

        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }
}