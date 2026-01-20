<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\SearchHistory;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->q;
        
        // Log search
        if ($query && auth()->check()) {
            SearchHistory::create([
                'user_id' => auth()->id(),
                'query' => $query,
                'results_count' => 0, // Will update after search
            ]);
        }

        $products = Product::active()
            ->inStock()
            ->when($query, function($q) use ($query) {
                $q->where(function($q2) use ($query) {
                    $q2->where('name', 'like', "%{$query}%")
                       ->orWhere('description', 'like', "%{$query}%")
                       ->orWhere('sku', 'like', "%{$query}%")
                       ->orWhere('part_number', 'like', "%{$query}%");
                });
            })
            ->when($request->category, function($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->when($request->brand, function($q) use ($request) {
                $q->where('brand_id', $request->brand);
            })
            ->when($request->min_price, function($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            })
            ->when($request->max_price, function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            })
            ->when($request->rating, function($q) use ($request) {
                $q->where('rating', '>=', $request->rating);
            })
            ->when($request->on_sale, function($q) {
                $q->onSale();
            })
            ->when($request->sort, function($q) use ($request) {
                match($request->sort) {
                    'price_asc' => $q->orderBy('price'),
                    'price_desc' => $q->orderByDesc('price'),
                    'newest' => $q->latest(),
                    'rating' => $q->orderByDesc('rating'),
                    'sales' => $q->orderByDesc('sales_count'),
                    'views' => $q->orderByDesc('views_count'),
                    default => $q->orderByDesc('created_at'),
                };
            }, function($q) {
                $q->orderByDesc('created_at');
            })
            ->with(['store', 'primaryImage', 'category', 'brand'])
            ->paginate(24);

        // Update search history with results count
        if ($query && auth()->check()) {
            SearchHistory::where('user_id', auth()->id())
                ->where('query', $query)
                ->latest()
                ->first()
                ?->update(['results_count' => $products->total()]);
        }

        $categories = Category::withCount('products')->orderByDesc('products_count')->limit(10)->get();
        $brands = Brand::withCount('products')->orderByDesc('products_count')->limit(10)->get();

        $priceRange = [
            'min' => Product::active()->min('price'),
            'max' => Product::active()->max('price'),
        ];

        return view('search.index', compact('products', 'query', 'categories', 'brands', 'priceRange'));
    }

    public function suggestions(Request $request)
    {
        $query = $request->q;

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::active()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'price', 'sale_price']);

        $categories = Category::where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get(['id', 'name', 'slug']);

        $brands = Brand::where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get(['id', 'name', 'slug']);

        // Popular searches
        $popularSearches = SearchHistory::where('query', 'like', "{$query}%")
            ->select('query')
            ->groupBy('query')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5)
            ->pluck('query');

        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'suggestions' => $popularSearches,
        ]);
    }

    public function history()
    {
        $history = SearchHistory::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return response()->json($history);
    }

    public function clearHistory()
    {
        SearchHistory::where('user_id', auth()->id())->delete();

        return response()->json(['success' => true]);
    }

    public function advanced()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $makes = \App\Models\CarMake::active()->orderBy('name')->get();

        return view('search.advanced', compact('categories', 'brands', 'makes'));
    }
}
