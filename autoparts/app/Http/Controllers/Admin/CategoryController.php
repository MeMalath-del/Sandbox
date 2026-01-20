<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->withCount('products')->orderBy('sort_order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'slug' => $request->slug ?? Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured'),
            'show_in_menu' => $request->boolean('show_in_menu', true),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'تم إضافة التصنيف بنجاح');
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'products']);
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'slug' => $request->slug ?? Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'show_in_menu' => $request->boolean('show_in_menu'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف تصنيف يحتوي على منتجات');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'تم حذف التصنيف بنجاح');
    }
}
