<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ItemController extends Controller
{
    /**
     * Display a listing of the items with filtering and pagination.
     */
    public function index(Request $request): Response
    {
        $query = Item::with(['category', 'unit']);
        $limit = $request->input('limit', 10);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
            $query->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sortBy')) {
            if ($request->sortBy == 'default') {
                $query->orderBy('id', 'asc');
            }
            switch ($request->sortBy) {
                case 'name_az':
                    $query->orderBy('name', 'asc');
                    break;
                case 'price_low_high':
                    $query->orderBy('selling_price', 'asc');
                    break;
                case 'price_high_low':
                    $query->orderBy('selling_price', 'desc');
                    break;
                case 'lowest_stock':
                    $query->orderBy('minimum_stock', 'asc');
                    break;
            }
        }

        if ($request->has('category')) {
            if ($request->category == '' || $request->category == '0') {
            } else {
                $query->where('category_id', $request->category);
            }
        }

        $items = $query->paginate($limit)->withQueryString();

        return Inertia::render('Inventory/Item/Index', [
            'items' => $items,
            'filters' => $request->only(['search', 'category', 'limit']),
            'categories' => ItemCategory::all()
        ]);
    }

    /**
     * Show the form for creating a new item.
     */
    public function create(): Response
    {
        return Inertia::render('Inventory/Item/Create', [
            'categories' => ItemCategory::all(),
            'units' => Unit::all()
        ]);
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:items,sku|max:255',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'minimum_stock' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:item_categories,id',
            'unit_id' => 'required|exists:units,id',
        ]);

        $count = Item::where('category_id', $validated['category_id'])->count() + 1;
        $categoryCode = str_pad($validated['category_id'], 3, '0', STR_PAD_LEFT);
        $itemCode = str_pad($count, 4, '0', STR_PAD_LEFT);
        $validated['sku'] = "CAT{$categoryCode}-{$itemCode}"; // Generate SKU

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($validated);
        return redirect()->route('item.index');
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item): Response
    {
        return Inertia::render('Items/Show', [
            'item' => $item->load(['category', 'unit'])
        ]);
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item): Response
    {
        return Inertia::render('Items/Edit', [
            'item' => $item,
            'categories' => ItemCategory::all(),
            'units' => Unit::all()
        ]);
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'minimum_stock' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:item_categories,id',
            'unit_id' => 'required|exists:units,id',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($validated);
        return redirect()->route('item.index');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('item.index');
    }

    public function checkSku(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:255'
        ]);

        $exists = Item::where('sku', $request->sku)->exists();

        return response()->json(['exists' => $exists]);
    }
}
