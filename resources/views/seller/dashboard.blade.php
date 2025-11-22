<!DOCTYPE html>
<html>
<head>
    <title>Seller Dashboard</title>
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
            margin-bottom: 10px;
        }
        .welcome {
            color: #6b7280;
            margin-bottom: 30px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #8b5cf6;
        }
        .card h3 {
            margin: 0 0 10px 0;
            color: #1f2937;
        }
        .card p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }
        button {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seller Dashboard</h1>
        <p class="welcome">Selamat Datang, <strong>{{ Auth::user()->name }}</strong>!</p>
        <p class="welcome">Anda login sebagai <strong>Penjual</strong>.</p>
        
        <div class="grid">
            <div class="card">
                <h3>🏪 Toko Saya</h3>
                <p>Kelola informasi toko</p>
            </div>
            <div class="card">
                <h3>📦 Produk Saya</h3>
                <p>Kelola produk jualan</p>
            </div>
            <div class="card">
                <h3>📋 Pesanan Masuk</h3>
                <p>Lihat pesanan pembeli</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>