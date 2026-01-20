<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product.store', 'items.product.primaryImage']);
        
        $cartItems = $cart->items;
        $subtotal = $cart->items->sum('subtotal');
        $shipping = $subtotal >= 200 ? 0 : 25;
        $discount = session('cart_discount', 0);
        $total = $subtotal + $shipping - $discount;
        
        return view('cart.index', compact('cartItems', 'subtotal', 'shipping', 'discount', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:100',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        if (!$product->isInStock()) {
            return back()->with('error', 'المنتج غير متوفر حالياً');
        }
        
        $cart = $this->getOrCreateCart();
        $quantity = $request->quantity ?? 1;
        
        // Check max order quantity
        if ($product->max_order_quantity && $quantity > $product->max_order_quantity) {
            return back()->with('error', 'الحد الأقصى للطلب هو ' . $product->max_order_quantity);
        }
        
        // Check min order quantity
        if ($quantity < $product->min_order_quantity) {
            return back()->with('error', 'الحد الأدنى للطلب هو ' . $product->min_order_quantity);
        }
        
        $cart->addItem($product, $quantity);
        
        return back()->with('success', 'تمت إضافة المنتج إلى السلة');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:100',
        ]);
        
        $cart = $this->getOrCreateCart();
        
        if ($item->cart_id !== $cart->id) {
            abort(403);
        }
        
        if ($request->quantity <= 0) {
            $item->delete();
            return back()->with('success', 'تم حذف المنتج من السلة');
        }
        
        $cart->updateItemQuantity($item, $request->quantity);
        
        return back()->with('success', 'تم تحديث الكمية');
    }

    public function remove(CartItem $item)
    {
        $cart = $this->getOrCreateCart();
        
        if ($item->cart_id !== $cart->id) {
            abort(403);
        }
        
        $item->delete();
        
        return back()->with('success', 'تم حذف المنتج من السلة');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->clear();
        
        return back()->with('success', 'تم تفريغ السلة');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);
        
        $coupon = Coupon::where('code', $request->coupon_code)->first();
        
        if (!$coupon) {
            return back()->with('error', 'كود الخصم غير صحيح');
        }
        
        if (!$coupon->isValid()) {
            return back()->with('error', 'كود الخصم منتهي الصلاحية أو تم استخدامه');
        }
        
        $cart = $this->getOrCreateCart();
        $subtotal = $cart->items->sum('subtotal');
        
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return back()->with('error', 'الحد الأدنى للطلب هو ' . $coupon->min_order_amount . ' ر.س');
        }
        
        $discount = $coupon->calculateDiscount($subtotal);
        
        session(['cart_coupon' => $coupon->code, 'cart_discount' => $discount]);
        
        return back()->with('success', 'تم تطبيق كود الخصم');
    }

    protected function getOrCreateCart()
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }
        
        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }
}
