<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $store = auth()->user()->store;
        
        $query = $store->products()->with(['category', 'brand', 'primaryImage']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::all();

        return view('store.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $carMakes = \App\Models\CarMake::active()->orderBy('name')->get();

        return view('store.products.create', compact('categories', 'brands', 'carMakes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'weight' => 'nullable|numeric',
            'dimensions' => 'nullable|string',
            'part_number' => 'nullable|string',
            'oem_number' => 'nullable|string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:5120',
        ]);

        $product = auth()->user()->store->products()->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'part_number' => $request->part_number,
            'oem_number' => $request->oem_number,
            'status' => 'pending',
        ]);

        // Handle images
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('products', 'public');
            $product->images()->create([
                'image_path' => $path,
                'is_primary' => $index === 0,
                'sort_order' => $index,
            ]);
        }

        // Handle specifications
        if ($request->specs) {
            foreach ($request->specs as $spec) {
                if ($spec['name'] && $spec['value']) {
                    $product->specifications()->create([
                        'name' => $spec['name'],
                        'value' => $spec['value'],
                    ]);
                }
            }
        }

        // Handle car compatibility
        if ($request->compatibilities) {
            foreach ($request->compatibilities as $comp) {
                $product->compatibilities()->create([
                    'car_make_id' => $comp['make_id'],
                    'car_model_id' => $comp['model_id'],
                    'year_from' => $comp['year_from'] ?? null,
                    'year_to' => $comp['year_to'] ?? null,
                ]);
            }
        }

        return redirect()->route('store.products.index')->with('success', 'تم إضافة المنتج وسيتم مراجعته');
    }

    public function edit(Product $product)
    {
        if ($product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $categories = Category::with('children')->whereNull('parent_id')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $carMakes = \App\Models\CarMake::active()->orderBy('name')->get();
        $product->load(['images', 'specifications', 'compatibilities']);

        return view('store.products.edit', compact('product', 'categories', 'brands', 'carMakes'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
        ]);

        $product->update($request->only([
            'name', 'price', 'sale_price', 'quantity', 'description',
            'short_description', 'weight', 'dimensions', 'part_number', 'oem_number',
        ]));

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'sort_order' => $product->images()->count(),
                ]);
            }
        }

        return back()->with('success', 'تم تحديث المنتج');
    }

    public function destroy(Product $product)
    {
        if ($product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $product->update(['status' => 'deleted']);

        return back()->with('success', 'تم حذف المنتج');
    }

    public function updateStock(Request $request, Product $product)
    {
        if ($product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $product->update(['quantity' => $request->quantity]);

        return back()->with('success', 'تم تحديث المخزون');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'action' => 'required|in:activate,deactivate,delete,update_price',
        ]);

        $store = auth()->user()->store;
        $productIds = collect($request->products)->pluck('id');

        $products = Product::whereIn('id', $productIds)
            ->where('store_id', $store->id)
            ->get();

        foreach ($products as $product) {
            match($request->action) {
                'activate' => $product->update(['status' => 'active']),
                'deactivate' => $product->update(['status' => 'inactive']),
                'delete' => $product->update(['status' => 'deleted']),
                'update_price' => $product->update([
                    'price' => collect($request->products)->firstWhere('id', $product->id)['price'] ?? $product->price,
                ]),
            };
        }

        return back()->with('success', 'تم تحديث المنتجات');
    }

    public function import()
    {
        return view('store.products.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx|max:10240',
        ]);

        // Process CSV/Excel import
        // ... implementation

        return back()->with('success', 'تم استيراد المنتجات');
    }

    public function export()
    {
        $products = auth()->user()->store->products;

        // Generate CSV
        $headers = ['SKU', 'Name', 'Price', 'Quantity', 'Category', 'Status'];
        
        $callback = function() use ($products, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->sku,
                    $product->name,
                    $product->price,
                    $product->quantity,
                    $product->category?->name,
                    $product->status,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ]);
    }
}
