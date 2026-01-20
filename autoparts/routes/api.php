<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/search-suggestions', function (Request $request) {
    $query = $request->get('q');
    
    if (strlen($query) < 2) {
        return response()->json([]);
    }
    
    $products = Product::active()
        ->inStock()
        ->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('sku', 'like', "%{$query}%")
              ->orWhere('oem_number', 'like', "%{$query}%");
        })
        ->with('primaryImage')
        ->limit(8)
        ->get()
        ->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => number_format($product->current_price, 2),
                'image' => $product->image_url,
            ];
        });
    
    return response()->json($products);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
