<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductView;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    public function forYou()
    {
        $user = auth()->user();
        
        // Get user's viewed products categories
        $viewedCategories = ProductView::where('user_id', $user->id)
            ->join('products', 'product_views.product_id', '=', 'products.id')
            ->pluck('products.category_id')
            ->unique()
            ->take(5);

        // Get user's purchased categories
        $purchasedCategories = $user->orders()
            ->where('status', 'delivered')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->pluck('products.category_id')
            ->unique();

        $categories = $viewedCategories->merge($purchasedCategories)->unique()->take(10);

        $products = Product::active()
            ->inStock()
            ->whereIn('category_id', $categories)
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('product_id')
                    ->from('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.user_id', $user->id);
            })
            ->with(['store', 'primaryImage'])
            ->inRandomOrder()
            ->limit(20)
            ->get();

        return view('recommendations.for-you', compact('products'));
    }

    public function basedOnViews()
    {
        $user = auth()->user();
        
        $viewedProductIds = ProductView::where('user_id', $user->id)
            ->orderByDesc('viewed_at')
            ->limit(10)
            ->pluck('product_id');

        $products = Product::active()
            ->inStock()
            ->whereIn('id', $viewedProductIds)
            ->with(['store', 'primaryImage'])
            ->get();

        // Get similar products
        $similar = Product::active()
            ->inStock()
            ->whereIn('category_id', $products->pluck('category_id'))
            ->whereNotIn('id', $viewedProductIds)
            ->with(['store', 'primaryImage'])
            ->limit(12)
            ->get();

        return view('recommendations.based-on-views', compact('products', 'similar'));
    }

    public function frequentlyBoughtTogether(Product $product)
    {
        // Find products that are often bought together
        $relatedProducts = DB::table('order_items as oi1')
            ->join('order_items as oi2', 'oi1.order_id', '=', 'oi2.order_id')
            ->join('products', 'oi2.product_id', '=', 'products.id')
            ->where('oi1.product_id', $product->id)
            ->where('oi2.product_id', '!=', $product->id)
            ->where('products.status', 'active')
            ->select('oi2.product_id', DB::raw('COUNT(*) as frequency'))
            ->groupBy('oi2.product_id')
            ->orderByDesc('frequency')
            ->limit(6)
            ->pluck('product_id');

        $products = Product::whereIn('id', $relatedProducts)
            ->with(['store', 'primaryImage'])
            ->get();

        return response()->json($products);
    }

    public function similarProducts(Product $product)
    {
        $products = Product::active()
            ->inStock()
            ->where('id', '!=', $product->id)
            ->where(function($q) use ($product) {
                $q->where('category_id', $product->category_id)
                  ->orWhere('brand_id', $product->brand_id);
            })
            ->with(['store', 'primaryImage'])
            ->orderByDesc('rating')
            ->limit(8)
            ->get();

        return response()->json($products);
    }

    public function trending()
    {
        $products = Product::active()
            ->inStock()
            ->where('created_at', '>=', now()->subDays(30))
            ->orderByDesc('views_count')
            ->with(['store', 'primaryImage'])
            ->limit(20)
            ->get();

        return view('recommendations.trending', compact('products'));
    }

    public function deals()
    {
        $products = Product::active()
            ->inStock()
            ->onSale()
            ->orderByRaw('(price - sale_price) / price DESC')
            ->with(['store', 'primaryImage'])
            ->paginate(24);

        return view('recommendations.deals', compact('products'));
    }
}
