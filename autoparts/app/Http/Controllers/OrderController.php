<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['store', 'items.product'])
            ->latest()
            ->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        
        $order->load(['store', 'items.product', 'statusHistory', 'delivery.driver']);
        
        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        $cart = Cart::where('user_id', auth()->id())->with(['items.product.store'])->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }
        
        $addresses = auth()->user()->addresses;
        $defaultAddress = auth()->user()->defaultAddress;
        
        return view('orders.checkout', compact('cart', 'addresses', 'defaultAddress'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|in:cash_on_delivery,credit_card,wallet',
            'shipping_method' => 'required|in:standard,express',
        ]);
        
        $cart = Cart::where('user_id', auth()->id())->with(['items.product.store'])->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }
        
        $address = auth()->user()->addresses()->findOrFail($request->address_id);
        
        try {
            DB::beginTransaction();
            
            // Group items by store
            $itemsByStore = $cart->items->groupBy('store_id');
            
            foreach ($itemsByStore as $storeId => $items) {
                $subtotal = $items->sum('total_price');
                $shippingCost = $request->shipping_method === 'express' ? 25 : 15;
                $taxRate = 15;
                $taxAmount = $subtotal * ($taxRate / 100);
                $total = $subtotal + $shippingCost + $taxAmount;
                
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'store_id' => $storeId,
                    'address_id' => $address->id,
                    'status' => 'pending',
                    'payment_status' => $request->payment_method === 'cash_on_delivery' ? 'pending' : 'pending',
                    'payment_method' => $request->payment_method,
                    'shipping_method' => $request->shipping_method,
                    'shipping_name' => $address->recipient_name,
                    'shipping_phone' => $address->recipient_phone,
                    'shipping_address' => $address->full_address,
                    'shipping_city' => $address->city,
                    'shipping_region' => $address->region,
                    'shipping_postal_code' => $address->postal_code,
                    'shipping_latitude' => $address->latitude,
                    'shipping_longitude' => $address->longitude,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'tax_amount' => $taxAmount,
                    'tax_rate' => $taxRate,
                    'total' => $total,
                    'customer_notes' => $request->notes,
                ]);
                
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_sku' => $item->product->sku,
                        'product_image' => $item->product->image_url,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total' => $item->total_price,
                    ]);
                    
                    // Update product stock
                    $item->product->decrement('quantity', $item->quantity);
                    $item->product->increment('reserved_quantity', $item->quantity);
                }
                
                // Log status
                $order->statusHistory()->create([
                    'status' => 'pending',
                    'comment' => 'تم إنشاء الطلب',
                ]);
            }
            
            // Clear cart
            $cart->clear();
            
            DB::commit();
            
            return redirect()->route('orders.index')->with('success', 'تم تقديم طلبك بنجاح');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء معالجة طلبك: ' . $e->getMessage());
        }
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'لا يمكن إلغاء هذا الطلب');
        }
        
        $order->updateStatus('cancelled', auth()->id(), 'تم الإلغاء بواسطة العميل');
        
        // Restore product stock
        foreach ($order->items as $item) {
            $item->product->increment('quantity', $item->quantity);
            $item->product->decrement('reserved_quantity', $item->quantity);
        }
        
        return back()->with('success', 'تم إلغاء الطلب بنجاح');
    }

    public function track(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        
        $order->load(['statusHistory', 'delivery']);
        
        return view('orders.track', compact('order'));
    }
}
