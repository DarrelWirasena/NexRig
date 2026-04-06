<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        // Check if coupon exists
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon code not found.'
            ], 404);
        }

        // Check if coupon is valid
        if (!$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is expired, inactive, or has reached its usage limit.'
            ], 422);
        }

        // Calculate cart subtotal
        $cartItems = $this->getCartSubtotal();

        // Check minimum purchase
        if ($cartItems < $coupon->min_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum purchase of Rp ' . number_format($coupon->min_purchase, 0, ',', '.') . ' required to use this coupon.'
            ], 422);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($cartItems);

        // Save to session
        session([
            'coupon' => [
                'id'       => $coupon->id,
                'code'     => $coupon->code,
                'type'     => $coupon->type,
                'value'    => $coupon->value,
                'discount' => $discount,
            ]
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Coupon applied successfully!',
            'code'     => $coupon->code,
            'discount' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'type'     => $coupon->type,
            'value'    => $coupon->formattedValue(),
        ]);
    }

    public function remove(Request $request)
    {
        session()->forget('coupon');

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
        ]);
    }

    private function getCartSubtotal(): float
    {
        $subtotal = 0;

        if (Auth::check()) {
            $items = \App\Models\CartItem::where('user_id', Auth::id())
                ->with('product')
                ->get();

            foreach ($items as $item) {
                if ($item->product) {
                    $subtotal += $item->product->price * $item->quantity;
                }
            }
        } else {
            $cart = session()->get('cart', []);
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
        }

        return $subtotal;
    }
}