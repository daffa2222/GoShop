<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SellerController extends Controller
{
    public function dashboard()
    {
        $products = Product::where('seller_id', auth()->id())->get();
        return view('seller.dashboard', compact('products'));
    }

    public function pending()
    {
        return view('seller.pending');
    }
}
