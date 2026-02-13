<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session;

class MoveCartToDatabase
{
    public function handle(Login $event)
    {
        // 1. Ambil keranjang session (jika ada)
        $sessionCart = Session::get('cart', []);

        if (count($sessionCart) > 0) {
            $userId = $event->user->id;

            foreach ($sessionCart as $productId => $details) {
                // 2. Cek apakah user sudah punya barang ini di DB?
                $dbItem = CartItem::where('user_id', $userId)
                                  ->where('product_id', $productId)
                                  ->first();

                if ($dbItem) {
                    // Jika sudah ada, tambahkan quantity-nya
                    $dbItem->quantity += $details['quantity'];
                    $dbItem->save();
                } else {
                    // Jika belum ada, buat baru
                    CartItem::create([
                        'user_id' => $userId,
                        'product_id' => $productId,
                        'quantity' => $details['quantity']
                    ]);
                }
            }

            // 3. Hapus session cart karena sudah pindah ke DB
            Session::forget('cart');
        }
    }
}