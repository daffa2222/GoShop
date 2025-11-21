<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar semua user
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Menampilkan daftar seller yang pending
    public function pendingSellers()
    {
        $sellers = User::where('role', 'seller')
                      ->where('status', 'pending')
                      ->paginate(10);

        return view('admin.sellers.pending', compact('sellers'));
    }

    // Approve seller
    public function approveSeller(User $user)
    {
        if ($user->role !== 'seller') {
            return back()->with('error', 'User bukan seller!');
        }

        $user->update(['status' => 'approved']);

        return back()->with('success', 'Seller berhasil disetujui!');
    }

    // Reject seller
    public function rejectSeller(User $user)
    {
        if ($user->role !== 'seller') {
            return back()->with('error', 'User bukan seller!');
        }

        $user->update(['status' => 'rejected']);

        return back()->with('success', 'Seller berhasil ditolak!');
    }

    // Menampilkan form create user
    public function create()
    {
        return view('admin.users.create');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,seller,buyer'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = $validated['role'] === 'seller' ? 'pending' : 'approved';

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // Menampilkan detail user
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // Menampilkan form edit user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,seller,buyer',
            'status' => 'required|in:pending,approved,rejected',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}