<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        $productIds = session('compare_products', []);
        
        if ($request->has('ids')) {
            $productIds = explode(',', $request->ids);
        }

        $products = Product::whereIn('id', $productIds)
            ->with(['category', 'brand', 'store', 'specifications', 'primaryImage', 'images'])
            ->get();

        // Get all unique specifications
        $allSpecs = $products->flatMap(function($product) {
            return $product->specifications->pluck('name');
        })->unique()->values();

        return view('compare.index', compact('products', 'allSpecs'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $compareProducts = session('compare_products', []);

        if (count($compareProducts) >= 4) {
            return response()->json(['success' => false, 'message' => 'يمكنك مقارنة 4 منتجات كحد أقصى']);
        }

        if (in_array($request->product_id, $compareProducts)) {
            return response()->json(['success' => false, 'message' => 'المنتج موجود بالفعل في المقارنة']);
        }

        $compareProducts[] = $request->product_id;
        session(['compare_products' => $compareProducts]);

        return response()->json([
            'success' => true, 
            'message' => 'تم إضافة المنتج للمقارنة',
            'count' => count($compareProducts),
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $compareProducts = session('compare_products', []);
        $compareProducts = array_diff($compareProducts, [$request->product_id]);
        session(['compare_products' => array_values($compareProducts)]);

        return response()->json([
            'success' => true,
            'message' => 'تم إزالة المنتج من المقارنة',
            'count' => count($compareProducts),
        ]);
    }

    public function clear()
    {
        session()->forget('compare_products');

        return response()->json([
            'success' => true,
            'message' => 'تم مسح قائمة المقارنة',
        ]);
    }

    public function count()
    {
        return response()->json([
            'count' => count(session('compare_products', [])),
        ]);
    }
}
