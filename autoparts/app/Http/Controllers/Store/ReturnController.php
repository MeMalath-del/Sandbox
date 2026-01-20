<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $store = auth()->user()->store;

        $query = ReturnRequest::where('store_id', $store->id)
            ->with(['user', 'order', 'items.orderItem.product']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $returns = $query->latest()->paginate(20);

        $stats = [
            'pending' => ReturnRequest::where('store_id', $store->id)->where('status', 'pending')->count(),
            'approved' => ReturnRequest::where('store_id', $store->id)->where('status', 'approved')->count(),
            'completed' => ReturnRequest::where('store_id', $store->id)->where('status', 'completed')->count(),
            'rejected' => ReturnRequest::where('store_id', $store->id)->where('status', 'rejected')->count(),
        ];

        return view('store.returns.index', compact('returns', 'stats'));
    }

    public function show(ReturnRequest $return)
    {
        if ($return->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $return->load(['user', 'order', 'items.orderItem.product', 'images', 'statusHistory']);

        return view('store.returns.show', compact('return'));
    }

    public function approve(Request $request, ReturnRequest $return)
    {
        if ($return->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        $return->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'store_note' => $request->note,
        ]);

        $return->statusHistory()->create([
            'status' => 'approved',
            'note' => $request->note,
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', 'تم قبول طلب الإرجاع');
    }

    public function reject(Request $request, ReturnRequest $return)
    {
        if ($return->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $return->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $request->reason,
        ]);

        $return->statusHistory()->create([
            'status' => 'rejected',
            'note' => $request->reason,
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', 'تم رفض طلب الإرجاع');
    }

    public function receive(ReturnRequest $return)
    {
        if ($return->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $return->update([
            'status' => 'received',
            'received_at' => now(),
        ]);

        $return->statusHistory()->create([
            'status' => 'received',
            'note' => 'تم استلام المنتجات المرتجعة',
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', 'تم تأكيد استلام المرتجعات');
    }

    public function complete(Request $request, ReturnRequest $return)
    {
        if ($return->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $return->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Process refund
        if ($return->type === 'refund') {
            $wallet = $return->user->wallet ?? $return->user->wallet()->create(['balance' => 0]);
            $wallet->credit($return->total_amount, 'استرداد طلب إرجاع #' . $return->id);
        }

        // Restore product quantities
        foreach ($return->items as $item) {
            $item->orderItem->product->increment('quantity', $item->quantity);
        }

        $return->statusHistory()->create([
            'status' => 'completed',
            'note' => 'تم إتمام عملية الإرجاع',
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', 'تم إتمام عملية الإرجاع');
    }
}
