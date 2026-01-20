<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $store = auth()->user()->store;
        
        $query = $store->orders()->with(['user', 'items.product']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        $stats = [
            'pending' => $store->orders()->where('status', 'pending')->count(),
            'processing' => $store->orders()->where('status', 'processing')->count(),
            'shipped' => $store->orders()->where('status', 'shipped')->count(),
            'delivered' => $store->orders()->where('status', 'delivered')->count(),
        ];

        return view('store.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        if ($order->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $order->load(['user', 'items.product', 'address', 'statusHistories', 'delivery']);

        return view('store.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,processing,ready_for_pickup,shipped,cancelled',
            'note' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'note' => $request->note,
            'changed_by' => auth()->id(),
        ]);

        // Send notification to customer
        // ...

        return back()->with('success', 'تم تحديث حالة الطلب');
    }

    public function printInvoice(Order $order)
    {
        if ($order->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $order->load(['user', 'items.product', 'address', 'store']);

        return view('store.orders.invoice', compact('order'));
    }

    public function printShippingLabel(Order $order)
    {
        if ($order->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $order->load(['user', 'address', 'store']);

        return view('store.orders.shipping-label', compact('order'));
    }

    public function bulkPrint(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'type' => 'required|in:invoice,shipping_label',
        ]);

        $store = auth()->user()->store;
        $orders = Order::whereIn('id', $request->orders)
            ->where('store_id', $store->id)
            ->with(['user', 'items.product', 'address'])
            ->get();

        return view('store.orders.bulk-print', compact('orders'));
    }

    public function assignDriver(Request $request, Order $order)
    {
        if ($order->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $delivery = $order->delivery()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'driver_id' => $request->driver_id,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]
        );

        $order->update(['status' => 'shipped']);

        return back()->with('success', 'تم تعيين السائق');
    }

    public function cancel(Request $request, Order $order)
    {
        if ($order->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'لا يمكن إلغاء هذا الطلب');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason,
        ]);

        // Restore product quantities
        foreach ($order->items as $item) {
            $item->product->increment('quantity', $item->quantity);
        }

        // Refund if paid
        if ($order->payment_status === 'paid') {
            $wallet = $order->user->wallet ?? $order->user->wallet()->create(['balance' => 0]);
            $wallet->credit($order->total, 'استرداد طلب ملغي: ' . $order->order_number);
        }

        return back()->with('success', 'تم إلغاء الطلب');
    }

    public function analytics(Request $request)
    {
        $store = auth()->user()->store;
        $period = $request->period ?? 'month';

        $startDate = match($period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };

        $orders = $store->orders()
            ->where('created_at', '>=', $startDate)
            ->get();

        $stats = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->where('status', 'delivered')->sum('total'),
            'average_order' => $orders->avg('total'),
            'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
        ];

        $dailyOrders = $store->orders()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = $store->products()
            ->withCount(['orderItems as sold' => function($q) use ($startDate) {
                $q->whereHas('order', fn($q2) => $q2->where('created_at', '>=', $startDate));
            }])
            ->orderByDesc('sold')
            ->limit(10)
            ->get();

        return view('store.orders.analytics', compact('stats', 'dailyOrders', 'topProducts', 'period'));
    }
}
