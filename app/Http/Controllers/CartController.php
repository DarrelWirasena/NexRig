<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    // =================================================================
    // 1. INDEX (View Page)
    // =================================================================
    public function index()
    {
        $title = 'Your Cart';
        $cartItems = $this->cartService->getCartData();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->price * $item->quantity;
        }

        $tax = $total * config('shop.tax_rate');
        $grandTotal = $total + $tax;

        return view('cart.index', [
            'cart'       => $cartItems,
            'total'      => $total,
            'tax'        => $tax,
            'grandTotal' => $grandTotal,
            'title'      => $title
        ]);
    }

    // =================================================================
    // 2. STORE (Add to Cart)
    // =================================================================
    public function store(Request $request, $id)
    {
        // Load relasi lengkap untuk persiapan simpan session
        $product = Product::with(['images', 'series.category'])->findOrFail($id);

        $qty = (int) $request->input('quantity', 1);

        if (Auth::check()) {
            // --- DB LOGIC ---
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $qty;
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $id,
                    'quantity' => $qty
                ]);
            }
        } else {
            // --- SESSION LOGIC ---
            $cart = session()->get('cart', []);

            // Siapkan URL Gambar (String)
            $imgSrc = 'https://placehold.co/100';
            if ($product->images->count() > 0) {
                $imgSrc = $product->images->first()->src;
            }

            // Jika item belum ada, buat baru
            if (!isset($cart[$id])) {
                $cart[$id] = [
                    "name" => $product->name,
                    "quantity" => 0,
                    "price" => $product->price,
                    "image" => $imgSrc,
                    "id" => $id,
                    // PENTING: Category wajib ada untuk session
                    "category" => $product->series->category->name ?? 'Component'
                ];
            }

            // Tambah quantity
            $cart[$id]['quantity'] += $qty;
            session()->put('cart', $cart);
        }

        // Response AJAX
        if ($request->wantsJson() || $request->ajax()) {
            return $this->sendCartResponse('Product added successfully!', $id);
        }

        return redirect()->back()->with('success', 'Product added successfully!');
    }

    // =================================================================
    // 3. UPDATE (Change Quantity)
    // =================================================================
    public function update(Request $request)
    {
        // Validasi
        if (!$request->id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Product ID required'], 400);
            }
            return redirect()->back()->with('error', 'Invalid Request');
        }

        if (!isset($request->change) && !isset($request->quantity)) {
            return redirect()->back();
        }

        $id = $request->id;
        $targetQty = 1;

        // --- UPDATE LOGIC ---
        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())->where('product_id', $id)->first();
            if ($cartItem) {
                // Hitung Target Quantity
                if (isset($request->quantity)) {
                    $targetQty = (int) $request->quantity;
                } elseif (isset($request->change)) {
                    $targetQty = $cartItem->quantity + (int) $request->change;
                }

                // Simpan (Min 1)
                $targetQty = max(1, $targetQty);
                $cartItem->quantity = $targetQty;
                $cartItem->save();
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                // Hitung Target Quantity
                if (isset($request->quantity)) {
                    $targetQty = (int) $request->quantity;
                } elseif (isset($request->change)) {
                    $targetQty = $cart[$id]['quantity'] + (int) $request->change;
                }

                // Simpan (Min 1)
                $targetQty = max(1, $targetQty);
                $cart[$id]['quantity'] = $targetQty;
                session()->put('cart', $cart);
            }
        }

        // Response
        if ($request->wantsJson() || $request->ajax()) {
            return $this->sendCartResponse('Cart updated', $id);
        }

        return redirect()->back()->with('success', 'Cart updated');
    }

    // =================================================================
    // 4. DESTROY (Remove Item)
    // =================================================================
    public function destroy(Request $request, $id)
    {
        if (Auth::check()) {
            $deleted = CartItem::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->delete();

            if (!$deleted) {
                $response = $this->sendCartResponse('Item not found');
                $data = $response->getData(true);
                $data['success'] = false;
                $data['removedId'] = $id;
                return response()->json($data, 404);
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            $response = $this->sendCartResponse('Item removed from cart');
            $data = $response->getData(true);
            $data['removedId'] = $id;
            return response()->json($data);
        }

        return redirect()->back()->with('success', 'Item removed');
    }

    // =================================================================
    // HELPER: Generate JSON Response Standard
    // =================================================================
    private function sendCartResponse($message, $updatedItemId = null)
    {
        // 1. Ambil data terbaru yang SUDAH STANDARD (Array of Objects)
        $cartItems = $this->cartService->getCartData();

        // 2. Hitung Total & Qty Item Terkait
        $total = 0;
        $totalQty = 0;
        $updatedItemQty = 0;

        foreach ($cartItems as $item) {
            $total += $item->price * $item->quantity;
            $totalQty += $item->quantity;

            if ($updatedItemId && $item->row_id == $updatedItemId) {
                $updatedItemQty = $item->quantity;
            }
        }

        $tax = $total * config('shop.tax_rate'); // PPN 11% (Contoh)
        $grandTotal = $total + $tax;

        // 3. Render View dengan data 'items' (Sesuai perbaikan view sebelumnya)
        $cartHtml = view('components.mini-cart-items', ['items' => $cartItems])->render();

        return response()->json([
            'success' => true,
            'message' => $message,
            'cartHtml' => $cartHtml,
            'cartCount' => $totalQty, // Jumlah total item (bukan jumlah jenis produk)
            'subtotal' => 'Rp ' . number_format($total, 0, ',', '.'),
            'tax' => 'Rp ' . number_format($tax, 0, ',', '.'),
            'grand_total' => 'Rp ' . number_format($grandTotal, 0, ',', '.'),
            'item_quantity' => $updatedItemQty
        ]);
    }
}
