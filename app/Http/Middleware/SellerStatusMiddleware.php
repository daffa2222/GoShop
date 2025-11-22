<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Pastikan user adalah seller
        if ($user->role !== 'seller') {
            return $next($request);
        }

        // Jika seller belum disetujui (pending atau rejected), redirect ke halaman pending
        if (in_array($user->status, ['pending', 'rejected'])) {
            // Kecuali jika sedang mengakses route seller.pending atau seller.delete-account
            if (!$request->routeIs('seller.pending') && !$request->routeIs('seller.delete-account')) {
                return redirect()->route('seller.pending');
            }
        }

        // Jika seller sudah approved, tidak boleh akses halaman pending
        if ($user->status === 'approved' && $request->routeIs('seller.pending')) {
            return redirect()->route('seller.dashboard');
        }

        return $next($request);
    }
}