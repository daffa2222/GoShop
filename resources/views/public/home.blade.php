<!DOCTYPE html>
<html>
<head>
    <title>GoShop - Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #1f2937;
            margin-bottom: 20px;
        }
        .nav {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .nav a {
            margin-right: 20px;
            text-decoration: none;
            color: #3b82f6;
            font-weight: bold;
        }
        .nav a:hover {
            text-decoration: underline;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            background: #f9fafb;
        }
        .product-card h3 {
            margin: 0 0 10px 0;
            color: #1f2937;
            font-size: 18px;
        }
        .product-card p {
            margin: 5px 0;
            color: #6b7280;
            font-size: 14px;
        }
        .price {
            font-size: 20px;
            font-weight: bold;
            color: #10b981;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 GoShop - E-Commerce Platform</h1>
        
        <div class="nav">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                @elseif(Auth::user()->role === 'seller')
                    <a href="{{ route('seller.dashboard') }}">Seller Dashboard</a>
                @elseif(Auth::user()->role === 'buyer')
                    <a href="{{ route('buyer.dashboard') }}">Buyer Dashboard</a>
                @endif
                <a href="{{ route('profile.edit') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: bold;">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endguest
            
            <a href="{{ route('products.index') }}">All Products</a>
        </div>

        <h2>✨ Produk Rekomendasi</h2>
        
        @if($products->count() > 0)
            <div class="products">
                @foreach($products as $product)
                    <div class="product-card">
                        <h3>{{ $product->name }}</h3>
                        <p>{{ Str::limit($product->description, 80) }}</p>
                        <p><strong>Kategori:</strong> {{ $product->category->name }}</p>
                        <p><strong>Toko:</strong> {{ $product->store->name }}</p>
                        <p><strong>Stok:</strong> {{ $product->stock }}</p>
                        <div class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p>Belum ada produk tersedia.</p>
        @endif
    </div>
</body>
</html>