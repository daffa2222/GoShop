<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    // Menampilkan informasi toko
    public function index()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return view('seller.store.create');
        }

        return view('seller.store.index', compact('store'));
    }

    // Menampilkan form create toko
    public function create()
    {
        // Cek apakah seller sudah punya toko
        if (Auth::user()->store) {
            return redirect()->route('seller.store.index')->with('info', 'Anda sudah memiliki toko.');
        }

        return view('seller.store.create');
    }

    // Menyimpan toko baru
    public function store(Request $request)
    {
        // Cek apakah seller sudah punya toko
        if (Auth::user()->store) {
            return redirect()->route('seller.store.index')->with('info', 'Anda sudah memiliki toko.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stores,name',
            'description' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('stores', 'public');
        }

        Store::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'logo' => $logoPath
        ]);

        return redirect()->route('seller.store.index')->with('success', 'Toko berhasil dibuat!');
    }

    // Menampilkan form edit toko
    public function edit()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->route('seller.store.create')->with('error', 'Anda belum memiliki toko.');
        }

        return view('seller.store.edit', compact('store'));
    }

    // Update informasi toko
    public function update(Request $request)
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->route('seller.store.create')->with('error', 'Anda belum memiliki toko.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stores,name,' . $store->id,
            'description' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $validated['logo'] = $request->file('logo')->store('stores', 'public');
        }

        $store->update($validated);

        return redirect()->route('seller.store.index')->with('success', 'Informasi toko berhasil diupdate!');
    }

    // Hapus toko (opsional, hati-hati karena akan menghapus semua produk)
    public function destroy()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->route('seller.dashboard')->with('error', 'Anda tidak memiliki toko.');
        }

        // Cek apakah masih ada produk
        if ($store->products()->count() > 0) {
            return back()->with('error', 'Toko tidak bisa dihapus karena masih ada produk!');
        }

        // Hapus logo jika ada
        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }

        $store->delete();

        return redirect()->route('seller.dashboard')->with('success', 'Toko berhasil dihapus!');
    }
}