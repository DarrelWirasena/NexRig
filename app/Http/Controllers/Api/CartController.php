<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends ApiController
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): JsonResponse
    {
        $cart = $this->cartService->getCartData();

        return $this->successResponse($cart, 'Cart retrieved successfully');
    }

    public function store(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->notFoundResponse('Product not found');
        }

        $qty = (int) $request->input('quantity', 1);

        // ── Validasi stok ─────────────────────────────────────────────────────
        if ($product->track_stock) {
            $existing   = CartItem::where('user_id', auth()->id())
                ->where('product_id', $id)->first();
            $currentQty = $existing?->quantity ?? 0;
            $totalQty   = $currentQty + $qty;

            if ($product->stock <= 0) {
                return $this->errorResponse('Stok ' . $product->name . ' habis.', 422);
            }

            if ($totalQty > $product->stock) {
                $sisa = $product->stock - $currentQty;
                $msg  = $sisa <= 0
                    ? 'Kamu sudah menambahkan semua stok yang tersedia (' . $product->stock . ' unit).'
                    : 'Hanya bisa menambah ' . $sisa . ' lagi. Stok tersisa: ' . $product->stock . ' unit.';

                return $this->errorResponse($msg, 422);
            }
        }
        // ─────────────────────────────────────────────────────────────────────

        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $qty);
        } else {
            CartItem::create([
                'user_id'    => auth()->id(),
                'product_id' => $id,
                'quantity'   => $qty,
            ]);
        }

        $cart = $this->cartService->getCartData();

        return $this->createdResponse($cart, 'Product added to cart');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product  = Product::find($id);
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $id)->first();

        if (!$cartItem) {
            return $this->notFoundResponse('Cart item not found');
        }

        $targetQty = isset($request->quantity)
            ? (int) $request->quantity
            : $cartItem->quantity + (int) ($request->change ?? 0);

        $targetQty = max(1, $targetQty);

        // ── Validasi stok saat update quantity ────────────────────────────────
        if ($product && $product->track_stock && $targetQty > $product->stock) {
            return $this->errorResponse(
                'Stok ' . $product->name . ' hanya tersisa ' . $product->stock . ' unit.',
                422
            );
        }
        // ─────────────────────────────────────────────────────────────────────

        $cartItem->update(['quantity' => $targetQty]);

        $cart = $this->cartService->getCartData();

        return $this->successResponse($cart, 'Cart updated');
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = CartItem::where('user_id', auth()->id())
            ->where('product_id', $id)->delete();

        if (!$deleted) {
            return $this->notFoundResponse('Cart item not found');
        }

        $cart = $this->cartService->getCartData();

        return $this->successResponse($cart, 'Item removed from cart');
    }

    public function checkout(Request $request): JsonResponse
    {
        // Validasi stok semua item sebelum lanjut checkout
        $stockErrors = $this->cartService->validateStock();

        if (!empty($stockErrors)) {
            return $this->errorResponse(implode(' ', $stockErrors), 422);
        }

        $cart = $this->cartService->checkout(auth()->id());

        return $this->successResponse($cart, 'Checkout successful');
    }
}