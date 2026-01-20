<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $activeFlashSale = FlashSale::active()
            ->with(['items.product.primaryImage'])
            ->first();

        $upcomingFlashSales = FlashSale::upcoming()
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        $pastFlashSales = FlashSale::ended()
            ->orderByDesc('ends_at')
            ->limit(10)
            ->get();

        return view('flash-sales.index', compact('activeFlashSale', 'upcomingFlashSales', 'pastFlashSales'));
    }

    public function show(FlashSale $flashSale)
    {
        $flashSale->load(['items.product.primaryImage', 'items.product.store']);

        $remainingTime = now()->diffInSeconds($flashSale->ends_at, false);
        
        return view('flash-sales.show', compact('flashSale', 'remainingTime'));
    }

    public function notify(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'email' => 'required_without:user_id|email',
        ]);

        $flashSale->notifications()->firstOrCreate([
            'user_id' => auth()->id(),
            'email' => $request->email ?? auth()->user()->email,
        ]);

        return back()->with('success', 'سيتم إشعارك عند بدء التخفيضات');
    }

    public function purchase(Request $request, FlashSaleItem $item)
    {
        if (!$item->flashSale->isActive()) {
            return back()->with('error', 'انتهت فترة التخفيضات');
        }

        if ($item->sold_quantity >= $item->quantity_limit) {
            return back()->with('error', 'نفدت الكمية المتاحة');
        }

        // Check purchase limit per user
        $userPurchases = $item->purchases()->where('user_id', auth()->id())->sum('quantity');
        if ($userPurchases >= $item->per_user_limit) {
            return back()->with('error', 'وصلت للحد الأقصى للشراء');
        }

        // Add to cart with flash sale price
        $cart = auth()->user()->cart ?? auth()->user()->cart()->create();
        
        $cartItem = $cart->items()->firstOrCreate(
            ['product_id' => $item->product_id],
            [
                'quantity' => 1,
                'price' => $item->sale_price,
                'flash_sale_item_id' => $item->id,
            ]
        );

        return redirect()->route('cart.index')->with('success', 'تم إضافة المنتج إلى السلة');
    }
}
