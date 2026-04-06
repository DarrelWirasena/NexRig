<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Ambil & format data cart (standardized object).
     * Digunakan oleh CartController dan CheckoutController.
     */
    public function getCartData(): array
    {
        $cartItems = [];

        if (Auth::check()) {
            $dbItems = CartItem::where('user_id', Auth::id())
                ->with(['product.images', 'product.series.category'])
                ->get();

            foreach ($dbItems as $item) {
                // Self-healing: hapus jika produk sudah dihapus
                if (!$item->product) {
                    $item->delete();
                    continue;
                }

                $cartItems[] = (object) [
                    'row_id'      => $item->product_id,
                    'product_id'  => $item->product_id,
                    'name'        => $item->product->name,
                    'price'       => $item->product->price,
                    'image'       => $item->product->images->first()->src ?? 'https://placehold.co/100',
                    'quantity'    => $item->quantity,
                    'category'    => $item->product->series->category->name ?? 'Component',
                    'stock'       => $item->product->stock,        // ← baru
                    'track_stock' => $item->product->track_stock,  // ← baru
                ];
            }
        } else {
            $sessionCart = session()->get('cart', []);

            foreach ($sessionCart as $productId => $details) {
                // Ambil stok terbaru dari DB untuk guest juga
                $product = Product::find($productId);

                $cartItems[] = (object) [
                    'row_id'      => $productId,
                    'product_id'  => $productId,
                    'name'        => $details['name'],
                    'price'       => $details['price'],
                    'image'       => $details['image'],
                    'quantity'    => $details['quantity'],
                    'category'    => $details['category'] ?? 'Component',
                    'stock'       => $product?->stock ?? 0,
                    'track_stock' => $product?->track_stock ?? true,
                ];
            }
        }

        return $cartItems;
    }

    // 🔥 TAMBAHAN 1: Ambil data keranjang khusus yang di-ceklis saja
    public function getCartDataByIds(array $selectedIds): array
    {
        $allCart = $this->getCartData();
        return array_filter($allCart, function ($item) use ($selectedIds) {
            // Cocokkan row_id dengan array ID yang dikirim dari checkbox
            return in_array((string) $item->row_id, $selectedIds);
        });
    }

    /**
     * Validasi stok semua item di cart.
     * Return array of error messages, kosong jika semua aman.
     */
    // 🔥 UBAH: Tambahkan parameter opsional $cartItems
    public function validateStock(array $cartItems = null): array
    {
        $errors    = [];
        // Jika tidak ada parameter yang dikirim, cek semua keranjang
        $itemsToCheck = $cartItems ?? $this->getCartData();

        foreach ($itemsToCheck as $item) {
            if (!$item->track_stock) continue;

            $product = Product::find($item->product_id);

            if (!$product) {
                $errors[] = "{$item->name} tidak lagi tersedia.";
                continue;
            }

            if ($product->stock < $item->quantity) {
                $available = $product->stock;
                $errors[]  = $available > 0
                    ? "Stok {$item->name} hanya tersisa {$available} unit (kamu memesan {$item->quantity})."
                    : "Stok {$item->name} habis.";
            }
        }

        return $errors;
    }

    /**
     * Kurangi stok semua item setelah order berhasil dibuat.
     * Dipanggil di dalam DB::transaction di CheckoutController.
     */
    // 🔥 UBAH: Fungsi ini sekarang butuh data item mana yang dikurangi
    public function decrementStockForItems(array $cartItems): void
    {
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);
            $product?->decrementStock($item->quantity);
        }
    }

    // 🔥 TAMBAHAN 2: Fungsi hapus khusus item yang dicheckout saja
    public function clearSelectedCart(array $selectedIds): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->whereIn('product_id', $selectedIds)
                ->delete();
        } else {
            $cart = session()->get('cart', []);
            foreach ($selectedIds as $id) {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }
    }

     /**
     * Kosongkan keranjang setelah checkout berhasil.
     */
    public function clearCart(): void
    {
        if (Auth::check()) {
            // Hapus semua isi keranjang di database untuk user yang login
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            // Hapus session keranjang untuk guest (tamu)
            session()->forget('cart');
        }
    }
}