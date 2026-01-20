<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\BundleItem;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::active()
            ->with(['items.product.primaryImage', 'store'])
            ->orderByDesc('discount_percentage')
            ->paginate(24);

        return view('bundles.index', compact('bundles'));
    }

    public function show(Bundle $bundle)
    {
        $bundle->load(['items.product.images', 'store', 'reviews']);

        $originalPrice = $bundle->items->sum(fn($item) => $item->product->price * $item->quantity);
        $savings = $originalPrice - $bundle->price;

        return view('bundles.show', compact('bundle', 'originalPrice', 'savings'));
    }

    public function addToCart(Bundle $bundle)
    {
        $cart = auth()->user()->cart ?? auth()->user()->cart()->create();

        foreach ($bundle->items as $item) {
            $existingItem = $cart->items()->where('product_id', $item->product_id)->first();
            
            if ($existingItem) {
                $existingItem->increment('quantity', $item->quantity);
            } else {
                $cart->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'bundle_id' => $bundle->id,
                ]);
            }
        }

        // Apply bundle discount
        session(['bundle_discount' => [
            'bundle_id' => $bundle->id,
            'discount' => $bundle->items->sum(fn($item) => $item->product->price * $item->quantity) - $bundle->price,
        ]]);

        return redirect()->route('cart.index')->with('success', 'تم إضافة الحزمة إلى السلة');
    }

    public function suggestBundle(Request $request)
    {
        // AI-powered bundle suggestion based on cart items
        $cartItems = auth()->user()->cart?->items()->with('product')->get() ?? collect();

        if ($cartItems->isEmpty()) {
            return response()->json(['bundles' => []]);
        }

        $categoryIds = $cartItems->pluck('product.category_id')->unique();

        $suggestedBundles = Bundle::active()
            ->whereHas('items.product', fn($q) => $q->whereIn('category_id', $categoryIds))
            ->with('items.product.primaryImage')
            ->limit(3)
            ->get();

        return response()->json(['bundles' => $suggestedBundles]);
    }
}
