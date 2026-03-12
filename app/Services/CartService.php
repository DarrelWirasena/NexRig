<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Ambil & Format Data Cart (Standardized Object)
     * Digunakan oleh CartController dan CheckoutController
     * 
     * @return array Array of stdClass objects dengan struktur cart item
     */
    public function getCartData()
    {
        $cartItems = [];

        if (Auth::check()) {
            // A. LOGGED IN USER (Ambil dari Database)
            // Load relasi product, images, dan category agar tidak N+1 Query
            $dbItems = CartItem::where('user_id', Auth::id())
                ->with(['product.images', 'product.series.category'])
                ->get();

            foreach ($dbItems as $item) {
                // Self-Healing: Hapus item jika produknya sudah dihapus dari DB toko
                if (!$item->product) {
                    $item->delete();
                    continue;
                }

                // Standardisasi ke Object
                $cartItems[] = (object) [
                    'row_id' => $item->product_id, // Gunakan Product ID sebagai key unik
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    // Gunakan accessor src jika ada, atau fallback manual
                    'image' => $item->product->images->first()->src ?? 'https://placehold.co/100',
                    'quantity' => $item->quantity,
                    'category' => $item->product->series->category->name ?? 'Component'
                ];
            }
        } else {
            // B. GUEST (Ambil dari Session)
            $sessionCart = session()->get('cart', []);

            foreach ($sessionCart as $productId => $details) {
                // Standardisasi ke Object (agar sama dengan DB logic)
                $cartItems[] = (object) [
                    'row_id' => $productId,
                    'product_id' => $productId,
                    'name' => $details['name'],
                    'price' => $details['price'],
                    'image' => $details['image'], // Session sudah simpan URL string
                    'quantity' => $details['quantity'],
                    'category' => $details['category'] ?? 'Component'
                ];
            }
        }

        return $cartItems;
    }
}
