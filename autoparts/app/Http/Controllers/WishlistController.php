<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->with(['product.store', 'product.primaryImage'])
            ->latest()
            ->paginate(20);
        
        return view('wishlist.index', compact('wishlist'));
    }

    public function add(Product $product)
    {
        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();
        
        if ($exists) {
            return back()->with('info', 'المنتج موجود بالفعل في المفضلة');
        }
        
        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);
        
        $product->increment('wishlist_count');
        
        return back()->with('success', 'تمت إضافة المنتج إلى المفضلة');
    }

    public function remove(Product $product)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();
        
        $product->decrement('wishlist_count');
        
        return back()->with('success', 'تم حذف المنتج من المفضلة');
    }
}
