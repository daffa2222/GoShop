<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Menampilkan form untuk memberikan review
    public function create(Request $request)
    {
        $productId = $request->query('product_id');
        $orderId = $request->query('order_id');

        $product = Product::findOrFail($productId);
        $order = Order::findOrFail($orderId);

        // Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Pastikan order sudah selesai
        if ($order->status !== 'Selesai') {
            return back()->with('error', 'Anda hanya bisa memberikan review setelah pesanan selesai!');
        }

        // Pastikan produk ada di order
        $orderItem = $order->orderItems()->where('product_id', $productId)->first();
        if (!$orderItem) {
            return back()->with('error', 'Produk tidak ditemukan dalam pesanan ini!');
        }

        // Cek apakah sudah pernah review
        $existingReview = Review::where('user_id', Auth::id())
                                ->where('product_id', $productId)
                                ->where('order_id', $orderId)
                                ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini!');
        }

        return view('buyer.reviews.create', compact('product', 'order'));
    }

    // Menyimpan review
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $order = Order::findOrFail($validated['order_id']);

        // Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Pastikan order sudah selesai
        if ($order->status !== 'Selesai') {
            return back()->with('error', 'Anda hanya bisa memberikan review setelah pesanan selesai!');
        }

        // Pastikan produk ada di order
        $orderItem = $order->orderItems()->where('product_id', $validated['product_id'])->first();
        if (!$orderItem) {
            return back()->with('error', 'Produk tidak ditemukan dalam pesanan ini!');
        }

        // Cek apakah sudah pernah review
        $existingReview = Review::where('user_id', Auth::id())
                                ->where('product_id', $validated['product_id'])
                                ->where('order_id', $validated['order_id'])
                                ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini!');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'order_id' => $validated['order_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);

        return redirect()->route('buyer.orders.show', $order->id)->with('success', 'Review berhasil ditambahkan!');
    }

    // Menampilkan daftar review milik user
    public function index()
    {
        $reviews = Review::where('user_id', Auth::id())
                        ->with('product', 'order')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('buyer.reviews.index', compact('reviews'));
    }

    // Menampilkan form edit review
    public function edit(Review $review)
    {
        // Pastikan review milik user yang login
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('buyer.reviews.edit', compact('review'));
    }

    // Update review
    public function update(Request $request, Review $review)
    {
        // Pastikan review milik user yang login
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $review->update($validated);

        return redirect()->route('buyer.reviews.index')->with('success', 'Review berhasil diupdate!');
    }

    // Hapus review
    public function destroy(Review $review)
    {
        // Pastikan review milik user yang login
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $review->delete();

        return back()->with('success', 'Review berhasil dihapus!');
    }
}