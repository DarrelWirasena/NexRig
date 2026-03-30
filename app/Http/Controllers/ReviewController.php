<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // ── Simpan ulasan baru ────────────────────────────────
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'title'    => ['nullable', 'string', 'max:100'],
            'body'     => ['nullable', 'string', 'max:2000'],
        ]);

        // Pastikan order milik user ini dan sudah completed
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Pastikan produk ada di dalam order tersebut
        $hasProduct = $order->items()->where('product_id', $product->id)->exists();
        abort_if(!$hasProduct, 403, 'Produk tidak ada dalam pesanan ini.');

        // Cek apakah sudah pernah review
        $exists = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('order_id', $order->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk produk ini.');
        }

        Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
            'order_id'   => $order->id,
            'rating'     => $request->rating,
            'title'      => $request->title,
            'body'        => $request->body,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }

    // ── Hapus ulasan milik sendiri ────────────────────────
    public function destroy(Review $review)
    {
        abort_if($review->user_id !== Auth::id(), 403);
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}