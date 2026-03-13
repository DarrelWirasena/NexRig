<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getCartData();
        $totals    = $this->calculateTotals($cartItems);

        return $this->view('cart.index', 'Your Cart', array_merge(
            ['cart' => $cartItems],
            $totals
        ));
    }

    public function store(Request $request, int $id)
    {
        $product = Product::with(['images', 'series.category'])->findOrFail($id);
        $qty     = (int) $request->input('quantity', 1);

        // ── Validasi stok sebelum add to cart ─────────────────────────────────
        if ($product->track_stock) {
            // Hitung qty yang sudah ada di cart saat ini
            $currentQty = 0;

            if (Auth::check()) {
                $existing   = CartItem::where('user_id', Auth::id())
                    ->where('product_id', $id)->first();
                $currentQty = $existing?->quantity ?? 0;
            } else {
                $cart       = session()->get('cart', []);
                $currentQty = $cart[$id]['quantity'] ?? 0;
            }

            $totalQty = $currentQty + $qty;

            if ($product->stock <= 0) {
                $msg = 'Stok ' . $product->name . ' habis.';
                return $request->wantsJson() || $request->ajax()
                    ? $this->errorResponse($msg, 422)
                    : back()->with('error', $msg);
            }

            if ($totalQty > $product->stock) {
                $sisa = $product->stock - $currentQty;
                $msg  = $sisa <= 0
                    ? 'Kamu sudah menambahkan semua stok yang tersedia (' . $product->stock . ' unit).'
                    : 'Hanya bisa menambah ' . $sisa . ' lagi. Stok tersisa: ' . $product->stock . ' unit.';

                return $request->wantsJson() || $request->ajax()
                    ? $this->errorResponse($msg, 422)
                    : back()->with('error', $msg);
            }
        }
        // ─────────────────────────────────────────────────────────────────────

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $id)->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $qty);
            } else {
                CartItem::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $id,
                    'quantity'   => $qty,
                ]);
            }
        } else {
            $cart   = session()->get('cart', []);
            $imgSrc = $product->images->first()->src ?? 'https://placehold.co/100';

            if (!isset($cart[$id])) {
                $cart[$id] = [
                    'name'     => $product->name,
                    'quantity' => 0,
                    'price'    => $product->price,
                    'image'    => $imgSrc,
                    'id'       => $id,
                    'category' => $product->series->category->name ?? 'Component',
                ];
            }

            $cart[$id]['quantity'] += $qty;
            session()->put('cart', $cart);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return $this->buildCartJsonResponse('Product added successfully!', $id);
        }

        return back()->with('success', 'Product added successfully!');
    }

    public function update(Request $request)
    {
        if (!$request->id) {
            return $request->wantsJson() || $request->ajax()
                ? $this->errorResponse('Product ID required', 400)
                : $this->redirectError('Invalid Request');
        }

        $id      = $request->id;
        $product = Product::find($id);

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $id)->first();

            if ($cartItem) {
                $targetQty = isset($request->quantity)
                    ? (int) $request->quantity
                    : $cartItem->quantity + (int) ($request->change ?? 0);

                $targetQty = max(1, $targetQty);

                // ── Validasi stok saat update quantity ────────────────────────
                if ($product && $product->track_stock && $targetQty > $product->stock) {
                    $msg = 'Stok ' . $product->name . ' hanya tersisa ' . $product->stock . ' unit.';
                    return $request->wantsJson() || $request->ajax()
                        ? $this->errorResponse($msg, 422)
                        : back()->with('error', $msg);
                }
                // ─────────────────────────────────────────────────────────────

                $cartItem->update(['quantity' => $targetQty]);
            }
        } else {
            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                $targetQty = isset($request->quantity)
                    ? (int) $request->quantity
                    : $cart[$id]['quantity'] + (int) ($request->change ?? 0);

                $targetQty = max(1, $targetQty);

                // ── Validasi stok saat update quantity (guest) ────────────────
                if ($product && $product->track_stock && $targetQty > $product->stock) {
                    $msg = 'Stok ' . $product->name . ' hanya tersisa ' . $product->stock . ' unit.';
                    return $request->wantsJson() || $request->ajax()
                        ? $this->errorResponse($msg, 422)
                        : back()->with('error', $msg);
                }
                // ─────────────────────────────────────────────────────────────

                $cart[$id]['quantity'] = $targetQty;
                session()->put('cart', $cart);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return $this->buildCartJsonResponse('Cart updated', $id);
        }

        return back()->with('success', 'Cart updated');
    }

    public function destroy(Request $request, int $id)
    {
        if (Auth::check()) {
            $deleted = CartItem::where('user_id', Auth::id())
                ->where('product_id', $id)->delete();

            if (!$deleted && ($request->wantsJson() || $request->ajax())) {
                return $this->errorResponse('Item not found', 404);
            }
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        if ($request->wantsJson() || $request->ajax()) {
            $response                  = $this->buildCartJsonResponse('Item removed from cart');
            $data                      = $response->getData(true);
            $data['data']['removedId'] = $id;
            return response()->json($data);
        }

        return back()->with('success', 'Item removed');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function calculateTotals(array $cartItems): array
    {
        $total      = array_reduce($cartItems, fn($carry, $item) => $carry + ($item->price * $item->quantity), 0);
        $tax        = $total * config('shop.tax_rate');
        $grandTotal = $total + $tax;

        return compact('total', 'tax', 'grandTotal');
    }

    private function buildCartJsonResponse(string $message, $updatedItemId = null): JsonResponse
    {
        $cartItems      = $this->cartService->getCartData();
        $totals         = $this->calculateTotals($cartItems);
        $totalQty       = array_sum(array_column((array) $cartItems, 'quantity'));
        $updatedItemQty = 0;

        foreach ($cartItems as $item) {
            if ($updatedItemId && $item->row_id == $updatedItemId) {
                $updatedItemQty = $item->quantity;
            }
        }

        $cartHtml = view('components.mini-cart-items', ['items' => $cartItems])->render();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => [
                'cartHtml'      => $cartHtml,
                'cartCount'     => $totalQty,
                'subtotal'      => 'Rp ' . number_format($totals['total'], 0, ',', '.'),
                'tax'           => 'Rp ' . number_format($totals['tax'], 0, ',', '.'),
                'grand_total'   => 'Rp ' . number_format($totals['grandTotal'], 0, ',', '.'),
                'item_quantity' => $updatedItemQty,
            ],
        ]);
    }
}