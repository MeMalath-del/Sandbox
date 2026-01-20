<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\CarMake;
use App\Models\ProductView;
use App\Models\SearchHistory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->inStock()->with(['store', 'primaryImage', 'category', 'brand']);
        
        // Filters
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->brand) {
            $query->where('brand_id', $request->brand);
        }
        
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        if ($request->featured) {
            $query->featured();
        }
        
        if ($request->sale) {
            $query->onSale();
        }
        
        if ($request->condition) {
            $query->where('condition', $request->condition);
        }
        
        // Sorting
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderByDesc('rating');
                break;
            case 'bestseller':
                $query->orderByDesc('sales_count');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        $products = $query->paginate(24);
        
        $categories = Category::active()->parents()->withCount('products')->get();
        $brands = Brand::active()->withCount('products')->get();
        
        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        $product->load(['store', 'images', 'specifications', 'compatibilities.carMake', 'compatibilities.carModel', 'category', 'brand']);
        
        // Record view
        ProductView::record($product);
        $product->incrementViews();
        
        // Related products
        $relatedProducts = Product::active()
            ->inStock()
            ->where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('category_id', $product->category_id)
                    ->orWhere('brand_id', $product->brand_id);
            })
            ->with(['store', 'primaryImage'])
            ->limit(4)
            ->get();
        
        // Reviews
        $reviews = $product->reviews()->approved()->with('user')->latest()->paginate(10);
        
        // Questions
        $questions = $product->questions()->approved()->with(['user', 'answers.user'])->latest()->paginate(5);
        
        return view('products.show', compact('product', 'relatedProducts', 'reviews', 'questions'));
    }

    public function category(Category $category)
    {
        $products = Product::active()
            ->inStock()
            ->where('category_id', $category->id)
            ->with(['store', 'primaryImage'])
            ->paginate(24);
        
        $subcategories = $category->children()->active()->get();
        
        return view('products.category', compact('category', 'products', 'subcategories'));
    }

    public function brand(Brand $brand)
    {
        $products = Product::active()
            ->inStock()
            ->where('brand_id', $brand->id)
            ->with(['store', 'primaryImage'])
            ->paginate(24);
        
        return view('products.brand', compact('brand', 'products'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('products.index');
        }
        
        $products = Product::active()
            ->inStock()
            ->search($query)
            ->with(['store', 'primaryImage'])
            ->paginate(24);
        
        // Record search
        SearchHistory::record($query, $products->total(), $request->all());
        
        return view('products.search', compact('products', 'query'));
    }
}
