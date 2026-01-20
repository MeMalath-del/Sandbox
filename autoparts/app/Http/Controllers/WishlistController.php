<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', auth()->id())
            ->with(['product.store', 'product.primaryImage'])
            ->latest()
            ->paginate(20);
        
        return view('wishlist.index', compact('wishlistItems'));
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

    public function remove(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }
        
        $wishlist->product->decrement('wishlist_count');
        $wishlist->delete();
        
        return back()->with('success', 'تم حذف المنتج من المفضلة');
    }
}
