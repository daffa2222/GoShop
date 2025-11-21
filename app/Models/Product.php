<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- WAJIB: Tambahkan Facade Str untuk membuat slug

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug', // Tetap ada di fillable
        'description',
        'price',
        'stock',
        'images',
        'rating_avg',
        'rating_count',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'rating_avg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // =========================================================
    // LOGIC OTOMATISASI SLUG (Solusi Error)
    // =========================================================
    
    /**
     * Membuat slug secara otomatis sebelum model disimpan (creating event).
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // 1. Buat slug dasar dari nama produk
            $slug = Str::slug($product->name);
            $count = 2;

            // 2. Cek keunikan slug. Jika sudah ada, tambahkan angka (contoh: produk-baru-2)
            while (static::where('slug', $slug)->exists()) {
                $slug = Str::slug($product->name) . '-' . $count++;
            }

            // 3. Set nilai slug pada objek produk
            $product->slug = $slug;
        });
    }

    // =========================================================
    // Relationships
    // =========================================================

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // =========================================================
    // Helper methods
    // =========================================================
    
    public function updateRating()
    {
        // Pastikan Anda telah membuat Model Review
        $this->rating_avg = $this->reviews()->avg('rating');
        $this->rating_count = $this->reviews()->count();
        $this->save();
    }
}