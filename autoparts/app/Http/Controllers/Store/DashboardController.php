<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        $stats = [
            'total_products' => $store->products()->count(),
            'active_products' => $store->products()->where('status', 'active')->count(),
            'out_of_stock' => $store->products()->where('quantity', 0)->count(),
            'total_orders' => $store->orders()->count(),
            'pending_orders' => $store->orders()->where('status', 'pending')->count(),
            'today_orders' => $store->orders()->whereDate('created_at', today())->count(),
            'today_revenue' => $store->orders()->whereDate('created_at', today())->where('status', 'delivered')->sum('total'),
            'month_revenue' => $store->orders()->whereMonth('created_at', now()->month)->where('status', 'delivered')->sum('total'),
            'total_reviews' => $store->reviews()->count(),
            'average_rating' => $store->reviews()->avg('rating') ?? 0,
            'followers' => $store->followers()->count(),
        ];

        $recentOrders = $store->orders()
            ->with(['user', 'items'])
            ->latest()
            ->limit(10)
            ->get();

        $lowStockProducts = $store->products()
            ->where('quantity', '<=', 5)
            ->where('quantity', '>', 0)
            ->limit(10)
            ->get();

        $recentReviews = $store->reviews()
            ->with(['user', 'product'])
            ->latest()
            ->limit(5)
            ->get();

        // Chart data
        $salesChart = $store->orders()
            ->where('status', 'delivered')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topSellingProducts = $store->products()
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(5)
            ->get();

        return view('store.dashboard', compact(
            'store', 'stats', 'recentOrders', 'lowStockProducts', 
            'recentReviews', 'salesChart', 'topSellingProducts'
        ));
    }

    public function settings()
    {
        $store = auth()->user()->store;
        
        return view('store.settings', compact('store'));
    }

    public function updateSettings(Request $request)
    {
        $store = auth()->user()->store;

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'working_hours' => 'nullable|string',
            'shipping_policy' => 'nullable|string',
            'return_policy' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'name', 'description', 'phone', 'email', 'address', 'city',
            'working_hours', 'shipping_policy', 'return_policy'
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('stores', 'public');
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('stores', 'public');
        }

        $store->update($data);

        return back()->with('success', 'تم تحديث إعدادات المتجر');
    }

    public function earnings()
    {
        $store = auth()->user()->store;

        $earnings = [
            'available' => $store->available_balance ?? 0,
            'pending' => $store->orders()->where('status', 'delivered')->whereNull('paid_to_store_at')->sum('store_amount'),
            'total' => $store->orders()->where('status', 'delivered')->sum('store_amount'),
            'withdrawn' => $store->withdrawals()->where('status', 'completed')->sum('amount'),
        ];

        $withdrawals = $store->withdrawals()->latest()->paginate(20);

        $transactions = $store->transactions()->latest()->paginate(20);

        return view('store.earnings', compact('earnings', 'withdrawals', 'transactions'));
    }

    public function requestWithdrawal(Request $request)
    {
        $store = auth()->user()->store;

        $request->validate([
            'amount' => 'required|numeric|min:100|max:' . ($store->available_balance ?? 0),
        ]);

        $store->withdrawals()->create([
            'amount' => $request->amount,
            'status' => 'pending',
            'bank_name' => $store->bank_name,
            'account_number' => $store->account_number,
            'account_holder' => $store->account_holder,
        ]);

        $store->decrement('available_balance', $request->amount);

        return back()->with('success', 'تم إرسال طلب السحب');
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->paginate(30);

        return view('store.notifications', compact('notifications'));
    }

    public function reviews()
    {
        $store = auth()->user()->store;

        $reviews = $store->reviews()
            ->with(['user', 'product'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => $store->reviews()->count(),
            'average' => $store->reviews()->avg('rating'),
            'five_star' => $store->reviews()->where('rating', 5)->count(),
            'four_star' => $store->reviews()->where('rating', 4)->count(),
            'three_star' => $store->reviews()->where('rating', 3)->count(),
            'two_star' => $store->reviews()->where('rating', 2)->count(),
            'one_star' => $store->reviews()->where('rating', 1)->count(),
        ];

        return view('store.reviews', compact('reviews', 'stats'));
    }

    public function replyToReview(Request $request, $reviewId)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review = \App\Models\Review::findOrFail($reviewId);
        
        if ($review->reviewable_type === \App\Models\Product::class) {
            $product = $review->reviewable;
            if ($product->store_id !== auth()->user()->store->id) {
                abort(403);
            }
        }

        $review->update(['store_reply' => $request->reply, 'replied_at' => now()]);

        return back()->with('success', 'تم إرسال الرد');
    }
}
