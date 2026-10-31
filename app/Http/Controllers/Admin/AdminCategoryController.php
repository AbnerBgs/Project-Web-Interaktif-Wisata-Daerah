<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('destinations')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'name_en'     => 'nullable|string|max:100',
            'icon'        => 'nullable|string|max:100',
            'color'       => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);
        $data['slug'] = Str::slug($request->name);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'name_en'     => 'nullable|string|max:100',
            'icon'        => 'nullable|string|max:100',
            'color'       => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);
        $data['slug'] = Str::slug($request->name);
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->destinations()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang masih memiliki destinasi.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus.');
    }
}
