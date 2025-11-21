<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Menampilkan daftar wishlist
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
                            ->with('product.category', 'product.store')
                            ->get();

        return view('buyer.wishlist.index', compact('wishlists'));
    }

    // Tambah produk ke wishlist
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        // Cek apakah produk sudah ada di wishlist
        $exists = Wishlist::where('user_id', Auth::id())
                         ->where('product_id', $validated['product_id'])
                         ->exists();

        if ($exists) {
            return back()->with('info', 'Produk sudah ada di wishlist!');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id']
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan ke wishlist!');
    }

    // Hapus produk dari wishlist
    public function destroy(Wishlist $wishlist)
    {
        // Pastikan wishlist milik user yang login
        if ($wishlist->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $wishlist->delete();

        return back()->with('success', 'Produk berhasil dihapus dari wishlist!');
    }

    // Toggle wishlist (untuk AJAX)
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())
                           ->where('product_id', $validated['product_id'])
                           ->first();

        if ($wishlist) {
            // Hapus dari wishlist
            $wishlist->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Produk dihapus dari wishlist'
            ]);
        } else {
            // Tambah ke wishlist
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id']
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Produk ditambahkan ke wishlist'
            ]);
        }
    }

    // Pindahkan semua wishlist ke cart
    public function moveAllToCart()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
                            ->with('product')
                            ->get();

        if ($wishlists->isEmpty()) {
            return back()->with('error', 'Wishlist kosong!');
        }

        foreach ($wishlists as $wishlist) {
            // Cek apakah produk sudah ada di cart
            $cartItem = \App\Models\Cart::where('user_id', Auth::id())
                                        ->where('product_id', $wishlist->product_id)
                                        ->first();

            if ($cartItem) {
                // Update quantity
                $cartItem->increment('quantity');
            } else {
                // Tambah ke cart
                \App\Models\Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $wishlist->product_id,
                    'quantity' => 1
                ]);
            }

            // Hapus dari wishlist
            $wishlist->delete();
        }

        return redirect()->route('buyer.cart.index')->with('success', 'Semua produk di wishlist berhasil dipindahkan ke keranjang!');
    }
}