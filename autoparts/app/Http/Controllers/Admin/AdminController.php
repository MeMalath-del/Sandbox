<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use App\Models\Driver;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // Add admin middleware
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_stores' => Store::count(),
            'total_drivers' => Driver::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'pending_stores' => Store::where('status', 'pending')->count(),
            'pending_drivers' => Driver::where('status', 'pending')->count(),
            'pending_products' => Product::where('status', 'pending')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_sales' => Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total'),
            'this_month_sales' => Order::whereMonth('created_at', now()->month)->where('payment_status', 'paid')->sum('total'),
        ];
        
        $recentOrders = Order::with(['user', 'store'])->latest()->limit(10)->get();
        $recentUsers = User::latest()->limit(10)->get();
        
        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentUsers'));
    }

    // Users Management
    public function users(Request $request)
    {
        $query = User::query();
        
        if ($request->type) {
            $query->where('user_type', $request->type);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }
        
        $users = $query->latest()->paginate(20);
        
        return view('admin.users', compact('users'));
    }

    public function showUser(User $user)
    {
        $user->load(['addresses', 'orders', 'store', 'driver', 'wallet']);
        return view('admin.users.show', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:pending,active,suspended,banned',
        ]);
        
        $user->update(['status' => $request->status]);
        
        return back()->with('success', 'تم تحديث حالة المستخدم');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'تم حذف المستخدم');
    }

    // Stores Management
    public function stores(Request $request)
    {
        $query = Store::with('user');
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $stores = $query->latest()->paginate(20);
        
        return view('admin.stores', compact('stores'));
    }

    public function showStore(Store $store)
    {
        $store->load(['user', 'products', 'orders']);
        return view('admin.stores.show', compact('store'));
    }

    public function approveStore(Store $store)
    {
        $store->update(['status' => 'active', 'is_verified' => true]);
        $store->user->update(['status' => 'active']);
        
        return back()->with('success', 'تم قبول المتجر');
    }

    public function suspendStore(Store $store)
    {
        $store->update(['status' => 'suspended']);
        return back()->with('success', 'تم إيقاف المتجر');
    }

    // Drivers Management
    public function drivers(Request $request)
    {
        $query = Driver::with('user');
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $drivers = $query->latest()->paginate(20);
        
        return view('admin.drivers.index', compact('drivers'));
    }

    public function showDriver(Driver $driver)
    {
        $driver->load(['user', 'vehicles', 'deliveries']);
        return view('admin.drivers.show', compact('driver'));
    }

    public function approveDriver(Driver $driver)
    {
        $driver->update(['status' => 'available', 'is_verified' => true]);
        $driver->user->update(['status' => 'active']);
        
        return back()->with('success', 'تم قبول الموصل');
    }

    // Products Management
    public function products(Request $request)
    {
        $query = Product::with(['store', 'category']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $products = $query->latest()->paginate(20);
        
        return view('admin.products', compact('products'));
    }

    public function showProduct(Product $product)
    {
        $product->load(['store', 'category', 'brand', 'images', 'specifications']);
        return view('admin.products.show', compact('product'));
    }

    public function approveProduct(Product $product)
    {
        $product->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);
        
        return back()->with('success', 'تم قبول المنتج');
    }

    public function rejectProduct(Request $request, Product $product)
    {
        $request->validate(['reason' => 'required|string']);
        
        $product->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);
        
        return back()->with('success', 'تم رفض المنتج');
    }

    // Orders Management
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'store']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->latest()->paginate(20);
        
        return view('admin.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'store', 'items.product', 'statusHistory', 'delivery.driver']);
        return view('admin.orders.show', compact('order'));
    }

    // Settings
    public function settings()
    {
        $settings = [];
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        // Update settings logic
        return back()->with('success', 'تم تحديث الإعدادات');
    }

    // Reports
    public function reports()
    {
        return view('admin.reports.index');
    }

    public function salesReport()
    {
        $sales = Order::where('payment_status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(total) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderByDesc('date')
            ->paginate(30);
        
        return view('admin.reports.sales', compact('sales'));
    }
}
