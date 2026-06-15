<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('category_name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['category_name' => 'required|string|max:100|unique:categories,category_name']);
        Category::create(['category_name' => $request->category_name, 'status' => 1]);
        return redirect()->route('admin.categories.index')->with('success', 'เพิ่มหมวดหมู่เรียบร้อย');
    }

    public function show(string $id) {}

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['category_name' => 'required|string|max:100|unique:categories,category_name,' . $category->category_id . ',category_id']);
        $category->update(['category_name' => $request->category_name]);
        return redirect()->route('admin.categories.index')->with('success', 'แก้ไขหมวดหมู่เรียบร้อย');
    }

    public function destroy(Category $category)
    {
        if ($category->lostItems()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'ไม่สามารถลบได้ เนื่องจากมีทรัพย์สินอยู่ในหมวดหมู่นี้');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'ลบหมวดหมู่เรียบร้อย');
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['status' => !$category->status]);
        return response()->json(['success' => true]);
    }
}
