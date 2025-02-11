<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->get();
        $categories = ItemCategory::all();

        return Inertia::render('Inventory/Item/Index', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/Item/Create', []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:50|unique:items,sku',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:50|unique:items,barcode',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'variations' => 'nullable|array',
            'cogs' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('items', 'public') : null;

        Item::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'barcode' => $request->barcode,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'variations' => json_encode($request->variations),
            'cogs' => $request->cogs,
            'price' => $request->price,
            'min_stock' => $request->min_stock,
            'weight' => $request->weight,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Item added successfully.');
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'sku' => 'required|string|max:50|unique:items,sku,' . $item->id,
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:50|unique:items,barcode,' . $item->id,
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'variations' => 'nullable|array',
            'cogs' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) Storage::disk('public')->delete($item->image);
            $imagePath = $request->file('image')->store('items', 'public');
        } else {
            $imagePath = $item->image;
        }

        $item->update([
            'sku' => $request->sku,
            'name' => $request->name,
            'barcode' => $request->barcode,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'variations' => json_encode($request->variations),
            'cogs' => $request->cogs,
            'price' => $request->price,
            'min_stock' => $request->min_stock,
            'weight' => $request->weight,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function show(Item $item)
    {
        $categories = ItemCategory::all();

        return Inertia::render('Inventory/Item/Edit', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }

    public function destroy(Item $item)
    {
        if ($item->image) Storage::disk('public')->delete($item->image);
        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
