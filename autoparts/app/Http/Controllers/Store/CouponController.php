<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;
        
        $coupons = Coupon::where('store_id', $store->id)
            ->withCount('usages')
            ->latest()
            ->paginate(20);

        return view('store.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $products = auth()->user()->store->products()->active()->get();
        $categories = \App\Models\Category::all();

        return view('store.coupons.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_public' => 'boolean',
        ]);

        $coupon = auth()->user()->store->coupons()->create([
            'code' => $request->code ?? Str::upper(Str::random(8)),
            'type' => $request->type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'usage_limit_per_user' => $request->usage_limit_per_user ?? 1,
            'starts_at' => $request->starts_at ?? now(),
            'expires_at' => $request->expires_at,
            'is_public' => $request->boolean('is_public'),
            'is_active' => true,
        ]);

        // Handle product restrictions
        if ($request->product_ids) {
            $coupon->products()->attach($request->product_ids);
        }

        // Handle category restrictions
        if ($request->category_ids) {
            $coupon->categories()->attach($request->category_ids);
        }

        return redirect()->route('store.coupons.index')->with('success', 'تم إنشاء كود الخصم');
    }

    public function edit(Coupon $coupon)
    {
        if ($coupon->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $products = auth()->user()->store->products()->active()->get();
        $categories = \App\Models\Category::all();
        $coupon->load(['products', 'categories']);

        return view('store.coupons.edit', compact('coupon', 'products', 'categories'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        if ($coupon->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $request->validate([
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $coupon->update($request->only([
            'discount_value', 'min_order_amount', 'max_discount',
            'usage_limit', 'usage_limit_per_user', 'expires_at', 'is_active',
        ]));

        return back()->with('success', 'تم تحديث كود الخصم');
    }

    public function destroy(Coupon $coupon)
    {
        if ($coupon->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $coupon->delete();

        return back()->with('success', 'تم حذف كود الخصم');
    }

    public function toggleActive(Coupon $coupon)
    {
        if ($coupon->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('success', 'تم تحديث حالة الكود');
    }
}
