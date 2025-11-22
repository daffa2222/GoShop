<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Penjual</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
        }
        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .pending { color: #f59e0b; }
        .rejected { color: #ef4444; }
        h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #1f2937;
        }
        p {
            color: #6b7280;
            margin-bottom: 20px;
        }
        button {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        button:hover {
            background-color: #dc2626;
        }
        .logout-btn {
            background-color: #6b7280;
            margin-top: 15px;
        }
        .logout-btn:hover {
            background-color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($user->status === 'pending')
            <div class="icon pending">⏱️</div>
            <h1>Akun Sedang Ditinjau</h1>
            <p>
                Akun penjual Anda sedang ditinjau oleh tim admin kami.<br>
                Mohon tunggu persetujuan.
            </p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
            
        @elseif($user->status === 'rejected')
            <div class="icon rejected">❌</div>
            <h1>Pendaftaran Ditolak</h1>
            <p>
                Maaf, pendaftaran penjual Anda ditolak oleh admin.
            </p>
            
            <form method="POST" action="{{ route('seller.delete-account') }}">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus akun?')">
                    Hapus Akun
                </button>
            </form>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        @endif
    </div>
</body>
</html>