<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name', 'ASC')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name'        => trim($request->input('name')),
            'description' => trim($request->input('description', '')),
        ]);

        return redirect()->route('categories.index')->with('flash_success', 'Kategori baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $this->validate($request, [
            'name'        => 'required|string|max:100|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->name = trim($request->input('name'));
        $category->description = trim($request->input('description', ''));
        $category->save();

        return redirect()->route('categories.index')->with('flash_success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // PRD Rule: Kategori dengan produk aktif tidak boleh dihapus
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('flash_error', 'Kategori "' . $category->name . '" tidak dapat dihapus karena masih menaungi ' . $category->products()->count() . ' produk.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('flash_success', 'Kategori berhasil dihapus.');
    }
}
