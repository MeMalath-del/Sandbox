<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Store;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Categories with product count
        $categories = Category::active()
            ->parents()
            ->withCount('products')
            ->orderBy('sort_order')
            ->limit(12)
            ->get();
        
        // Brands
        $brands = Brand::active()
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit(12)
            ->get();
        
        // Sale products
        $saleProducts = Product::active()
            ->inStock()
            ->onSale()
            ->with(['store', 'primaryImage', 'category'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Bestseller products
        $bestsellerProducts = Product::active()
            ->inStock()
            ->with(['store', 'primaryImage', 'category'])
            ->orderByDesc('sales_count')
            ->limit(8)
            ->get();
        
        // New arrivals
        $newProducts = Product::active()
            ->inStock()
            ->with(['store', 'primaryImage', 'category'])
            ->latest()
            ->limit(8)
            ->get();
        
        // Featured stores
        $stores = Store::active()
            ->withCount('products')
            ->orderByDesc('rating')
            ->limit(4)
            ->get();
        
        return view('home', compact(
            'categories',
            'brands',
            'saleProducts',
            'bestsellerProducts',
            'newProducts',
            'stores'
        ));
    }
}
