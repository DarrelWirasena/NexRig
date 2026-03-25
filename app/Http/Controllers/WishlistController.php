<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Halaman daftar wishlist user.
     */
    public function index()
    {
        $wishlists = Wishlist::with(['product.images', 'product.series'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle wishlist — tambah jika belum ada, hapus jika sudah ada.
     * Dipanggil via AJAX (fetch).
     */
    public function toggle(Request $request, $productId)
    {
        $userId = Auth::id();

        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            // Sudah ada → hapus (un-wishlist)
            $existing->delete();
            $wishlisted = false;
            $message    = 'Dihapus dari wishlist.';
        } else {
            // Belum ada → tambah
            Wishlist::create([
                'user_id'    => $userId,
                'product_id' => $productId,
            ]);
            $wishlisted = true;
            $message    = 'Ditambahkan ke wishlist!';
        }

        // Hitung total wishlist user (untuk badge)
        $count = Wishlist::where('user_id', $userId)->count();

        return response()->json([
            'wishlisted' => $wishlisted,
            'message'    => $message,
            'count'      => $count,
        ]);
    }

    /**
     * Hapus satu item dari halaman wishlist.
     */
    public function destroy($productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();
    
        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}