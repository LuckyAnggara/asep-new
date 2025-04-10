<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ItemCategoryController extends Controller
{
    public function index(): Response
    {
        $categories = ItemCategory::all();
        return Inertia::render('Inventory/Categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
        ]);

        ItemCategory::create($request->only('name'));

        return redirect()->back()->with('success', 'Category added successfully.');
    }

    public function update(Request $request, ItemCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($request->only('name'));

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy(ItemCategory $category)
    {
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
