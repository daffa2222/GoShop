<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerPendingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// ==================== PUBLIC ROUTES ====================
// Routes untuk user yang belum login (Guest/Public User)

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/products', [PublicController::class, 'products'])->name('products.index');
Route::get('/products/{product}', [PublicController::class, 'show'])->name('products.show');
Route::get('/categories/{category}/products', [PublicController::class, 'productsByCategory'])->name('categories.products');
Route::get('/search', [PublicController::class, 'search'])->name('products.search');

// ==================== AUTH ROUTES ====================
// Default Laravel authentication routes
require __DIR__.'/auth.php';

// Dashboard default (redirect based on role)
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'seller') {
        if ($user->status === 'approved') {
            return redirect()->route('seller.dashboard');
        } else {
            return redirect()->route('seller.pending');
        }
    } elseif ($user->role === 'buyer') {
        return redirect()->route('buyer.dashboard');
    }
    
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (semua role bisa akses)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== SELLER PENDING ROUTES ====================
// Routes untuk seller yang belum di-approve
Route::middleware('auth')->group(function () {
    Route::get('/seller/pending', [SellerPendingController::class, 'index'])->name('seller.pending');
    Route::delete('/seller/delete-account', [SellerPendingController::class, 'deleteAccount'])->name('seller.delete-account');
});

// ==================== ADMIN ROUTES ====================
// Routes khusus untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Seller Verification
    Route::get('/sellers/pending', [UserController::class, 'pendingSellers'])->name('sellers.pending');
    Route::post('/sellers/{user}/approve', [UserController::class, 'approveSeller'])->name('sellers.approve');
    Route::post('/sellers/{user}/reject', [UserController::class, 'rejectSeller'])->name('sellers.reject');
    
    // Category Management
    Route::resource('categories', CategoryController::class);
});

// ==================== SELLER ROUTES ====================
// Routes khusus untuk Seller (yang sudah approved)
Route::middleware(['auth', 'role:seller', 'seller.status'])->prefix('seller')->name('seller.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('seller.dashboard');
    })->name('dashboard');
    
    // Store Management
    Route::get('/store', [StoreController::class, 'index'])->name('store.index');
    Route::get('/store/create', [StoreController::class, 'create'])->name('store.create');
    Route::post('/store', [StoreController::class, 'store'])->name('store.store');
    Route::get('/store/edit', [StoreController::class, 'edit'])->name('store.edit');
    Route::put('/store', [StoreController::class, 'update'])->name('store.update');
    Route::delete('/store', [StoreController::class, 'destroy'])->name('store.destroy');
    
    // Product Management
    Route::resource('products', ProductController::class);
    
    // Order Management (Pesanan Masuk)
    Route::get('/orders', [OrderController::class, 'sellerOrders'])->name('orders.index');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});

// ==================== BUYER ROUTES ====================
// Routes khusus untuk Buyer
Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('buyer.dashboard');
    })->name('dashboard');
    
    // Cart Management
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/checkout', [OrderController::class, 'checkout'])->name('cart.checkout');
    
    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    
    // Review Management
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Wishlist Management (Advanced Feature)
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/move-to-cart', [WishlistController::class, 'moveAllToCart'])->name('wishlist.move-to-cart');
});