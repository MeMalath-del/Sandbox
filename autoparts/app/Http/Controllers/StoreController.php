<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::active()
            ->verified()
            ->withCount('products')
            ->orderByDesc('rating')
            ->paginate(20);
        
        return view('stores.index', compact('stores'));
    }

    public function show(Store $store)
    {
        $products = $store->products()->active()->inStock()->with('primaryImage')->latest()->paginate(12);
        
        $categories = Category::whereHas('products', function ($query) use ($store) {
            $query->where('store_id', $store->id)->active();
        })->get();
        
        $reviews = $store->reviews()->approved()->with('user')->latest()->limit(5)->get();
        
        return view('stores.show', compact('store', 'products', 'categories', 'reviews'));
    }

    public function follow(Store $store)
    {
        $user = auth()->user();
        
        if ($store->followers()->where('user_id', $user->id)->exists()) {
            $store->followers()->detach($user->id);
            return back()->with('success', 'تم إلغاء متابعة المتجر');
        }
        
        $store->followers()->attach($user->id);
        return back()->with('success', 'تم متابعة المتجر بنجاح');
    }

    // Store Dashboard Methods
    public function dashboard()
    {
        $store = $this->getStore();
        
        if (!$store) {
            return redirect()->route('store.create')->with('info', 'يرجى إنشاء متجرك أولاً');
        }
        
        $todayOrders = $store->orders()->whereDate('created_at', today())->count();
        $todaySales = $store->orders()->whereDate('created_at', today())->where('payment_status', 'paid')->sum('total');
        $pendingOrders = $store->orders()->where('status', 'pending')->count();
        $totalProducts = $store->products()->count();
        
        $recentOrders = $store->orders()->with('user')->latest()->limit(5)->get();
        $lowStockProducts = $store->products()->where('quantity', '<=', 5)->limit(5)->get();
        
        return view('store.dashboard', compact(
            'store', 'todayOrders', 'todaySales', 'pendingOrders', 
            'totalProducts', 'recentOrders', 'lowStockProducts'
        ));
    }

    public function products()
    {
        $store = $this->getStore();
        $products = $store->products()->with(['category', 'primaryImage'])->latest()->paginate(20);
        
        return view('store.products.index', compact('store', 'products'));
    }

    public function createProduct()
    {
        $store = $this->getStore();
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        
        return view('store.products.create', compact('store', 'categories', 'brands'));
    }

    public function storeProduct(Request $request)
    {
        $store = $this->getStore();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        
        $product = $store->products()->create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name) . '-' . uniqid(),
            'sku' => 'SKU-' . strtoupper(uniqid()),
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'status' => 'pending',
        ]);
        
        return redirect()->route('store.products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function orders()
    {
        $store = $this->getStore();
        $orders = $store->orders()->with(['user', 'items'])->latest()->paginate(20);
        
        return view('store.orders.index', compact('store', 'orders'));
    }

    public function showOrder(Order $order)
    {
        $store = $this->getStore();
        
        if ($order->store_id !== $store->id) {
            abort(403);
        }
        
        $order->load(['user', 'items.product', 'statusHistory', 'delivery']);
        
        return view('store.orders.show', compact('store', 'order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $store = $this->getStore();
        
        if ($order->store_id !== $store->id) {
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:confirmed,processing,ready_for_shipping,shipped,cancelled',
        ]);
        
        $order->updateStatus($request->status, auth()->id(), $request->comment);
        
        return back()->with('success', 'تم تحديث حالة الطلب');
    }

    public function settings()
    {
        $store = $this->getStore();
        return view('store.settings', compact('store'));
    }

    public function updateSettings(Request $request)
    {
        $store = $this->getStore();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);
        
        $store->update($request->only(['name', 'description', 'phone', 'email', 'address']));
        
        return back()->with('success', 'تم تحديث إعدادات المتجر');
    }

    protected function getStore()
    {
        return Store::where('user_id', auth()->id())->first();
    }
}
