<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // Homepage - Menampilkan produk rekomendasi
    public function index()
    {
        // Ambil produk secara acak untuk rekomendasi
        $products = Product::where('stock', '>', 0)
                          ->inRandomOrder()
                          ->limit(12)
                          ->get();

        $categories = Category::all();

        return view('public.home', compact('products', 'categories'));
    }

    // Product List - Menampilkan semua produk dengan filter & search
    public function products(Request $request)
    {
        $query = Product::where('stock', '>', 0)->with('category', 'store');

        // Search berdasarkan nama produk
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan harga minimum
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filter berdasarkan harga maksimum
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'latest');
        
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('public.products', compact('products', 'categories'));
    }

    // Product Detail - Menampilkan detail produk dengan review
    public function show(Product $product)
    {
        $product->load(['category', 'store', 'reviews.user']);

        // Hitung rata-rata rating
        $averageRating = $product->reviews()->avg('rating');
        $totalReviews = $product->reviews()->count();

        // Produk terkait dari kategori yang sama
        $relatedProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $product->id)
                                  ->where('stock', '>', 0)
                                  ->limit(4)
                                  ->get();

        return view('public.product-detail', compact('product', 'averageRating', 'totalReviews', 'relatedProducts'));
    }

    // Search Products - API untuk autocomplete search
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $products = Product::where('name', 'like', '%' . $query . '%')
                          ->where('stock', '>', 0)
                          ->limit(10)
                          ->get(['id', 'name', 'price', 'image']);

        return response()->json($products);
    }

    // Products by Category
    public function productsByCategory(Category $category)
    {
        $products = Product::where('category_id', $category->id)
                          ->where('stock', '>', 0)
                          ->paginate(12);

        $categories = Category::all();

        return view('public.products', compact('products', 'categories', 'category'));
    }

    // Products by Store
    public function productsByStore($storeId)
    {
        $products = Product::where('store_id', $storeId)
                          ->where('stock', '>', 0)
                          ->paginate(12);

        $categories = Category::all();

        return view('public.products', compact('products', 'categories'));
    }
}