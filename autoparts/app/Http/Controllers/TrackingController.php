<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Delivery;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
        ]);

        $order = Order::where('order_number', $request->order_number)->first();

        if (!$order) {
            return back()->with('error', 'رقم الطلب غير صحيح');
        }

        // If guest, verify with email or phone
        if (!auth()->check()) {
            if ($request->email && $order->user->email !== $request->email) {
                return back()->with('error', 'البريد الإلكتروني غير صحيح');
            }
        } else {
            if ($order->user_id !== auth()->id()) {
                return back()->with('error', 'هذا الطلب ليس لك');
            }
        }

        return redirect()->route('tracking.show', $order->order_number);
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product', 'statusHistories', 'delivery.driver', 'delivery.logs'])
            ->firstOrFail();

        $timeline = $this->buildTimeline($order);

        return view('tracking.show', compact('order', 'timeline'));
    }

    public function liveLocation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        
        if (!$order->delivery || !in_array($order->delivery->status, ['picked_up', 'in_transit'])) {
            return response()->json(['error' => 'التتبع المباشر غير متاح'], 400);
        }

        $delivery = $order->delivery;
        
        return response()->json([
            'driver' => [
                'name' => $delivery->driver->user->name,
                'phone' => $delivery->driver->user->phone,
                'vehicle' => $delivery->driver->vehicles()->first(),
            ],
            'location' => [
                'lat' => $delivery->current_latitude,
                'lng' => $delivery->current_longitude,
                'updated_at' => $delivery->location_updated_at,
            ],
            'eta' => $delivery->estimated_arrival_at,
            'status' => $delivery->status,
        ]);
    }

    protected function buildTimeline(Order $order)
    {
        $steps = [
            ['status' => 'pending', 'label' => 'تم استلام الطلب', 'icon' => 'bi-receipt'],
            ['status' => 'confirmed', 'label' => 'تم تأكيد الطلب', 'icon' => 'bi-check-circle'],
            ['status' => 'processing', 'label' => 'جاري التجهيز', 'icon' => 'bi-box'],
            ['status' => 'shipped', 'label' => 'تم الشحن', 'icon' => 'bi-truck'],
            ['status' => 'out_for_delivery', 'label' => 'في الطريق إليك', 'icon' => 'bi-geo-alt'],
            ['status' => 'delivered', 'label' => 'تم التوصيل', 'icon' => 'bi-check2-all'],
        ];

        $currentIndex = array_search($order->status, array_column($steps, 'status'));

        return collect($steps)->map(function($step, $index) use ($currentIndex, $order) {
            $step['completed'] = $index <= $currentIndex;
            $step['current'] = $index === $currentIndex;
            
            $history = $order->statusHistories->where('status', $step['status'])->first();
            $step['date'] = $history?->created_at;
            
            return $step;
        });
    }
}
