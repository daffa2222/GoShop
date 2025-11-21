<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan isi keranjang
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())
                    ->with('product')
                    ->get();
        
        $total = $carts->sum(function($cart) {
            return $cart->quantity * $cart->product->price;
        });

        return view('buyer.cart.index', compact('carts', 'total'));
    }

    // Tambah produk ke keranjang
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Cek stok
        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok produk tidak mencukupi!');
        }

        // Cek apakah produk sudah ada di cart
        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $validated['product_id'])
                    ->first();

        if ($cart) {
            // Update quantity jika sudah ada
            $newQuantity = $cart->quantity + $validated['quantity'];
            
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Stok produk tidak mencukupi!');
            }

            $cart->update(['quantity' => $newQuantity]);
        } else {
            // Tambah item baru ke cart
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity']
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Update quantity produk di keranjang
    public function update(Request $request, Cart $cart)
    {
        // Pastikan cart milik user yang login
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Cek stok
        if ($cart->product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok produk tidak mencukupi!');
        }

        $cart->update($validated);

        return back()->with('success', 'Keranjang berhasil diupdate!');
    }

    // Hapus produk dari keranjang
    public function destroy(Cart $cart)
    {
        // Pastikan cart milik user yang login
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $cart->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}