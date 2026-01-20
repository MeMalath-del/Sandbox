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
        $banners = Banner::active()->position('home_slider')->orderBy('sort_order')->get();
        
        $featuredCategories = Category::active()
            ->featured()
            ->parents()
            ->orderBy('sort_order')
            ->limit(8)
            ->get();
        
        $featuredBrands = Brand::active()
            ->featured()
            ->orderBy('sort_order')
            ->limit(12)
            ->get();
        
        $featuredProducts = Product::active()
            ->inStock()
            ->featured()
            ->with(['store', 'primaryImage'])
            ->latest()
            ->limit(8)
            ->get();
        
        $bestsellerProducts = Product::active()
            ->inStock()
            ->bestseller()
            ->with(['store', 'primaryImage'])
            ->orderByDesc('sales_count')
            ->limit(8)
            ->get();
        
        $newArrivals = Product::active()
            ->inStock()
            ->newArrival()
            ->with(['store', 'primaryImage'])
            ->latest()
            ->limit(8)
            ->get();
        
        $onSaleProducts = Product::active()
            ->inStock()
            ->onSale()
            ->with(['store', 'primaryImage'])
            ->latest()
            ->limit(8)
            ->get();
        
        $topStores = Store::active()
            ->verified()
            ->orderByDesc('rating')
            ->limit(6)
            ->get();
        
        return view('home', compact(
            'banners',
            'featuredCategories',
            'featuredBrands',
            'featuredProducts',
            'bestsellerProducts',
            'newArrivals',
            'onSaleProducts',
            'topStores'
        ));
    }
}
