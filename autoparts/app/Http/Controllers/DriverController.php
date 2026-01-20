<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function dashboard()
    {
        $driver = $this->getDriver();
        
        if (!$driver) {
            return redirect()->route('driver.create')->with('info', 'يرجى استكمال بيانات الموصل');
        }
        
        $todayDeliveries = $driver->deliveries()->whereDate('created_at', today())->count();
        $todayEarnings = $driver->deliveries()->whereDate('created_at', today())->where('status', 'delivered')->sum('driver_earning');
        $activeDelivery = $driver->deliveries()->whereIn('status', ['accepted', 'picking_up', 'picked_up', 'in_transit'])->first();
        $pendingDeliveries = Delivery::where('status', 'pending')->count();
        
        $recentDeliveries = $driver->deliveries()->with('order.store')->latest()->limit(5)->get();
        
        return view('driver.dashboard', compact(
            'driver', 'todayDeliveries', 'todayEarnings', 
            'activeDelivery', 'pendingDeliveries', 'recentDeliveries'
        ));
    }

    public function deliveries()
    {
        $driver = $this->getDriver();
        $deliveries = $driver->deliveries()->with(['order.store', 'order.user'])->latest()->paginate(20);
        
        return view('driver.deliveries.index', compact('driver', 'deliveries'));
    }

    public function availableDeliveries()
    {
        $driver = $this->getDriver();
        
        $deliveries = Delivery::where('status', 'pending')
            ->with(['order.store', 'order.user'])
            ->latest()
            ->paginate(20);
        
        return view('driver.deliveries.available', compact('driver', 'deliveries'));
    }

    public function showDelivery(Delivery $delivery)
    {
        $driver = $this->getDriver();
        
        $delivery->load(['order.store', 'order.user', 'order.items.product', 'logs']);
        
        return view('driver.deliveries.show', compact('driver', 'delivery'));
    }

    public function acceptDelivery(Delivery $delivery)
    {
        $driver = $this->getDriver();
        
        if ($delivery->status !== 'pending') {
            return back()->with('error', 'هذا التوصيل غير متاح');
        }
        
        $delivery->update([
            'driver_id' => $driver->id,
            'status' => 'accepted',
        ]);
        
        $delivery->updateStatus('accepted', 'تم قبول التوصيل');
        
        return redirect()->route('driver.deliveries.show', $delivery)->with('success', 'تم قبول التوصيل');
    }

    public function rejectDelivery(Delivery $delivery)
    {
        $driver = $this->getDriver();
        
        if ($delivery->driver_id !== $driver->id) {
            abort(403);
        }
        
        $delivery->update([
            'driver_id' => null,
            'status' => 'pending',
        ]);
        
        return back()->with('success', 'تم رفض التوصيل');
    }

    public function updateDeliveryStatus(Request $request, Delivery $delivery)
    {
        $driver = $this->getDriver();
        
        if ($delivery->driver_id !== $driver->id) {
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:picking_up,picked_up,in_transit,arrived,delivered,failed',
        ]);
        
        $delivery->updateStatus($request->status, $request->notes, $request->photo);
        
        // Update order status if delivered
        if ($request->status === 'delivered') {
            $delivery->order->updateStatus('delivered', $driver->user_id);
            $driver->addEarnings($delivery->driver_earning);
            $driver->increment('completed_deliveries');
        }
        
        return back()->with('success', 'تم تحديث حالة التوصيل');
    }

    public function earnings()
    {
        $driver = $this->getDriver();
        
        $totalEarnings = $driver->total_earnings;
        $thisMonthEarnings = $driver->deliveries()
            ->whereMonth('created_at', now()->month)
            ->where('status', 'delivered')
            ->sum('driver_earning');
        
        $earnings = $driver->deliveries()
            ->where('status', 'delivered')
            ->selectRaw('DATE(created_at) as date, SUM(driver_earning) as total')
            ->groupBy('date')
            ->orderByDesc('date')
            ->paginate(30);
        
        return view('driver.earnings', compact('driver', 'totalEarnings', 'thisMonthEarnings', 'earnings'));
    }

    public function ratings()
    {
        $driver = $this->getDriver();
        $reviews = $driver->reviews()->with('user')->latest()->paginate(20);
        
        return view('driver.ratings', compact('driver', 'reviews'));
    }

    public function settings()
    {
        $driver = $this->getDriver();
        $vehicles = $driver->vehicles;
        
        return view('driver.settings', compact('driver', 'vehicles'));
    }

    public function updateSettings(Request $request)
    {
        $driver = $this->getDriver();
        
        $driver->update($request->only([
            'auto_accept', 'accepts_cash', 'max_orders_per_day', 'service_areas'
        ]));
        
        return back()->with('success', 'تم تحديث الإعدادات');
    }

    public function updateStatus(Request $request)
    {
        $driver = $this->getDriver();
        
        $request->validate([
            'status' => 'required|in:available,busy,offline',
        ]);
        
        $driver->update(['status' => $request->status]);
        
        return back()->with('success', 'تم تحديث الحالة');
    }

    protected function getDriver()
    {
        return Driver::where('user_id', auth()->id())->first();
    }
}
