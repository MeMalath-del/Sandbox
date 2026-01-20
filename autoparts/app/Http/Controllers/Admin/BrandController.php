<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->orderBy('sort_order')->paginate(20);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:brands',
        ]);

        Brand::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'slug' => $request->slug ?? Str::slug($request->name),
            'description' => $request->description,
            'website' => $request->website,
            'country_of_origin' => $request->country_of_origin,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'تم إضافة الماركة بنجاح');
    }

    public function show(Brand $brand)
    {
        $brand->load('products');
        return view('admin.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:brands,slug,' . $brand->id,
        ]);

        $brand->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'slug' => $request->slug ?? Str::slug($request->name),
            'description' => $request->description,
            'website' => $request->website,
            'country_of_origin' => $request->country_of_origin,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'تم تحديث الماركة بنجاح');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف ماركة تحتوي على منتجات');
        }

        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'تم حذف الماركة بنجاح');
    }
}
