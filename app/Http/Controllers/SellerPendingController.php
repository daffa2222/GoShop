<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerPendingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Redirect jika bukan seller
        if ($user->role !== 'seller') {
            return redirect('/dashboard');
        }

        // Redirect jika sudah approved
        if ($user->status === 'approved') {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.pending', compact('user'));
    }

    public function deleteAccount()
    {
        $user = auth()->user();

        // Hanya seller yang rejected bisa delete
        if ($user->role === 'seller' && $user->status === 'rejected') {
            auth()->logout();
            $user->delete();
            return redirect('/')->with('success', 'Account deleted successfully');
        }

        return back()->with('error', 'Unauthorized action');
    }
}