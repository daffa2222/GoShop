<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p>Anda login sebagai <strong>Admin</strong>.</p>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800">Kelola Pengguna</h4>
                            <p class="text-gray-600 text-sm">Kelola semua pengguna</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800">Verifikasi Penjual</h4>
                            <p class="text-gray-600 text-sm">Setujui/Tolak penjual</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-800">Kategori</h4>
                            <p class="text-gray-600 text-sm">Kelola kategori produk</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>