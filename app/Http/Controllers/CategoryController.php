<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Support\Frontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return Frontend::render('categories.index', 'categories.index', [
            'categories' => Category::query()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return Frontend::render('categories.create', 'categories.create', [

        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Category::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]));

        return redirect()->route('categories.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(Category $category): RedirectResponse
    {
        return redirect()->route('categories.edit', $category);
    }

    public function edit(Category $category): View
    {
        return Frontend::render('categories.edit', 'categories.edit', [
            'category' => $category,

        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
        ]));

        return redirect()->route('categories.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Data berhasil dihapus.');
    }
}
