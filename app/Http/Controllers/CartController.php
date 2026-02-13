<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // =================================================================
    // HELPER: Ambil & Format Data Cart (DB atau Session)
    // =================================================================
    private function getCartData()
    {
        $cart = [];

        if (Auth::check()) {
            // A. LOGGED IN USER (Ambil dari Database)
            $items = CartItem::where('user_id', Auth::id())
                             ->with('product.images')
                             ->get();

            foreach ($items as $item) {
                // Self-Healing: Hapus item jika produknya sudah dihapus dari DB toko
                if (!$item->product) {
                    $item->delete();
                    continue; 
                }

                $image = $item->product->images->where('is_primary', true)->first();
                $imageUrl = $image ? $image->image_url : 'https://placehold.co/100';

                $cart[$item->product_id] = [
                    "name" => $item->product->name,
                    "quantity" => $item->quantity,
                    "price" => $item->product->price,
                    "image" => $imageUrl,
                    "id" => $item->product_id
                ];
            }
        } else {
            // B. GUEST (Ambil dari Session)
            $cart = session()->get('cart', []);
        }

        return $cart;
    }

    // =================================================================
    // 1. INDEX (View Page)
    // =================================================================
    public function index()
    {
        $cart = $this->getCartData();
        
        $total = 0;
        foreach($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // Sinkronisasi Session (Penting untuk View Blade)
        if (Auth::check()) {
            session()->put('cart', $cart); 
        }

        return view('cart.index', compact('cart', 'total'));
    }

    // =================================================================
    // 2. STORE (Add to Cart)
    // =================================================================
    public function store(Request $request, $id)
    {
        $product = Product::with('images')->findOrFail($id);
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
            
            // Siapkan data jika item belum ada
            if(!isset($cart[$id])) {
                $image = $product->images->where('is_primary', true)->first();
                $imageUrl = $image ? $image->image_url : 'https://placehold.co/100';
                
                $cart[$id] = [
                    "name" => $product->name,
                    "quantity" => 0, // Mulai dari 0, nanti ditambah di bawah
                    "price" => $product->price,
                    "image" => $imageUrl,
                    "id" => $id
                ];
            }
            
            $cart[$id]['quantity'] += $qty;
            session()->put('cart', $cart);
        }

        // Response
        if ($request->wantsJson() || $request->ajax()) {
            return $this->sendCartResponse('Product added successfully!');
        }

        return redirect()->back()->with('success', 'Product added successfully!');
    }

    // =================================================================
    // 3. UPDATE (Change Quantity) - DIPERBAIKI
    // =================================================================
    public function update(Request $request)
    {
        // 1. VALIDASI UTAMA: Cek ID saja dulu
        if(!$request->id) {
             if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Product ID required'], 400);
             }
             return redirect()->back()->with('error', 'Invalid Request');
        }

        // 2. CEK INPUT: Harus ada 'change' ATAU 'quantity'
        // Jika dua-duanya tidak ada, return error
        if (!isset($request->change) && !isset($request->quantity)) {
             return redirect()->back(); // Atau return JSON error
        }

        $id = $request->id;

        // Ambil Current Quantity (Logic Database vs Session)
        $currentQty = 0;
        
        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())->where('product_id', $id)->first();
            if ($cartItem) $currentQty = $cartItem->quantity;
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) $currentQty = $cart[$id]['quantity'];
        }

        // Tentukan Target Quantity
        $targetQty = $currentQty; 

        if (isset($request->quantity)) {
            // Case A: Input Manual (Set langsung jadi angka tersebut)
            // Code ini sekarang BISA JALAN karena validasi di atas sudah dilonggarkan
            $targetQty = (int) $request->quantity;
        } elseif (isset($request->change)) {
            // Case B: Tombol +/- (Relative)
            $targetQty = $currentQty + (int) $request->change;
        }

        // Pastikan minimal 1
        $targetQty = max(1, $targetQty);

        // Simpan Perubahan
        if (Auth::check()) {
            if (isset($cartItem)) {
                $cartItem->quantity = $targetQty;
                $cartItem->save();
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $targetQty;
                session()->put('cart', $cart);
            }
        }

        // Response AJAX / Redirect
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
            CartItem::where('user_id', Auth::id())->where('product_id', $id)->delete();
        } else {
            $cart = session()->get('cart', []);
            if(isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return $this->sendCartResponse('Item removed from cart');
        }

        return redirect()->back()->with('success', 'Item removed');
    }

    // =================================================================
    // HELPER: Generate JSON Response Standard
    // =================================================================
    private function sendCartResponse($message, $updatedItemId = null)
    {
        // Ambil data terbaru (DB atau Session)
        $cart = $this->getCartData();
        
        // Hitung Total
        $total = 0;
        foreach($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }
        $tax = $total * 0.11;
        $grandTotal = $total + $tax;

        // Render HTML Mini Cart
        $cartHtml = view('components.mini-cart-items', ['cart' => $cart])->render();

        return response()->json([
            'success' => true,
            'message' => $message,
            'cartHtml' => $cartHtml,
            'cartCount' => count($cart),
            'subtotal' => 'Rp ' . number_format($total, 0, ',', '.'),
            'tax' => 'Rp ' . number_format($tax, 0, ',', '.'),
            'grand_total' => 'Rp ' . number_format($grandTotal, 0, ',', '.'),
            'item_quantity' => ($updatedItemId && isset($cart[$updatedItemId])) ? $cart[$updatedItemId]['quantity'] : 0
        ]);
    }
}