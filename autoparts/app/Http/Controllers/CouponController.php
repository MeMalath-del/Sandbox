<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::active()
            ->public()
            ->orderByDesc('discount_value')
            ->paginate(20);

        return view('coupons.index', compact('coupons'));
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'كود الخصم غير صحيح']);
        }

        if (!$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'كود الخصم منتهي الصلاحية']);
        }

        if (!$coupon->canBeUsedBy(auth()->user())) {
            return response()->json(['success' => false, 'message' => 'لا يمكنك استخدام هذا الكود']);
        }

        $cart = auth()->user()->cart;
        $subtotal = $cart->subtotal;

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return response()->json([
                'success' => false, 
                'message' => 'الحد الأدنى للطلب ' . number_format($coupon->min_order_amount, 2) . ' ر.س'
            ]);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        session(['coupon_code' => $coupon->code, 'coupon_discount' => $discount]);

        return response()->json([
            'success' => true,
            'message' => 'تم تطبيق كود الخصم',
            'discount' => $discount,
            'formatted_discount' => number_format($discount, 2) . ' ر.س',
        ]);
    }

    public function remove()
    {
        session()->forget(['coupon_code', 'coupon_discount']);
        return back()->with('success', 'تم إزالة كود الخصم');
    }
}
