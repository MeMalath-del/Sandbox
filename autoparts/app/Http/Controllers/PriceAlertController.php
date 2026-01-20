<?php

namespace App\Http\Controllers;

use App\Models\PriceAlert;
use App\Models\Product;
use Illuminate\Http\Request;

class PriceAlertController extends Controller
{
    public function index()
    {
        $alerts = PriceAlert::where('user_id', auth()->id())
            ->with('product.primaryImage')
            ->latest()
            ->paginate(20);

        return view('price-alerts.index', compact('alerts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'target_price' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        $currentPrice = $product->sale_price ?? $product->price;

        if ($request->target_price >= $currentPrice) {
            return back()->with('error', 'السعر المستهدف يجب أن يكون أقل من السعر الحالي');
        }

        // Check for existing alert
        $existing = PriceAlert::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            $existing->update(['target_price' => $request->target_price]);
            return back()->with('success', 'تم تحديث تنبيه السعر');
        }

        PriceAlert::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'target_price' => $request->target_price,
            'original_price' => $currentPrice,
            'status' => 'active',
        ]);

        return back()->with('success', 'تم إنشاء تنبيه السعر');
    }

    public function destroy(PriceAlert $priceAlert)
    {
        if ($priceAlert->user_id !== auth()->id()) {
            abort(403);
        }

        $priceAlert->delete();

        return back()->with('success', 'تم حذف التنبيه');
    }

    public function toggleActive(PriceAlert $priceAlert)
    {
        if ($priceAlert->user_id !== auth()->id()) {
            abort(403);
        }

        $priceAlert->update([
            'status' => $priceAlert->status === 'active' ? 'paused' : 'active',
        ]);

        return back()->with('success', 'تم تحديث حالة التنبيه');
    }
}
