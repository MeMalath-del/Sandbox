<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;
use App\Models\ReturnItem;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::where('user_id', auth()->id())
            ->with(['order', 'items.orderItem.product'])
            ->latest()
            ->paginate(20);

        return view('returns.index', compact('returns'));
    }

    public function create(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'لا يمكن طلب الإرجاع إلا للطلبات المكتملة');
        }

        if ($order->delivered_at && $order->delivered_at->diffInDays(now()) > 14) {
            return back()->with('error', 'انتهت فترة الإرجاع (14 يوم)');
        }

        $order->load('items.product');

        return view('returns.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.order_item_id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.reason' => 'required|string|max:500',
            'type' => 'required|in:refund,exchange',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $return = ReturnRequest::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'store_id' => $order->store_id,
            'type' => $request->type,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        $totalAmount = 0;

        foreach ($request->items as $item) {
            $orderItem = OrderItem::findOrFail($item['order_item_id']);
            
            if ($orderItem->order_id !== $order->id) {
                continue;
            }

            $amount = $orderItem->unit_price * $item['quantity'];
            $totalAmount += $amount;

            ReturnItem::create([
                'return_id' => $return->id,
                'order_item_id' => $item['order_item_id'],
                'quantity' => $item['quantity'],
                'reason' => $item['reason'],
                'amount' => $amount,
            ]);
        }

        $return->update(['total_amount' => $totalAmount]);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('returns', 'public');
                $return->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('returns.show', $return)->with('success', 'تم إرسال طلب الإرجاع');
    }

    public function show(ReturnRequest $return)
    {
        if ($return->user_id !== auth()->id()) {
            abort(403);
        }

        $return->load(['order', 'items.orderItem.product', 'statusHistory']);

        return view('returns.show', compact('return'));
    }

    public function cancel(ReturnRequest $return)
    {
        if ($return->user_id !== auth()->id()) {
            abort(403);
        }

        if ($return->status !== 'pending') {
            return back()->with('error', 'لا يمكن إلغاء هذا الطلب');
        }

        $return->update(['status' => 'cancelled']);

        return back()->with('success', 'تم إلغاء طلب الإرجاع');
    }
}
