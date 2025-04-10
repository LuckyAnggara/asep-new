<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

use function Laravel\Prompts\error;

class ItemController extends Controller
{
    /**
     * Display a listing of the items with filtering and pagination.
     */
    public function index(Request $request): Response
    {
        $query = Item::with(['category', 'unit', 'lastBalances',]);
        $limit = $request->input('limit', 10);

        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        });

        $query->when($request->filled('category') && $request->category !== '0', function ($q) use ($request) {
            $q->where('item_category_id', $request->category);
        });

        $query->when($request->filled('sortBy'), function ($q) use ($request) {
            switch ($request->sortBy) {
                case 'default':
                    $q->orderBy('id', 'asc');
                    break;
                case 'name_az':
                    $q->orderBy('name', 'asc');
                    break;
                case 'price_low_high':
                    $q->orderBy('selling_price', 'asc');
                    break;
                case 'price_high_low':
                    $q->orderBy('selling_price', 'desc');
                    break;
                case 'lowest_stock':
                    $q->orderBy('minimum_stock', 'asc');
                    break;
            }
        });

        $items = $query->paginate($limit)->withQueryString();

        return Inertia::render('Inventory/Item/Index', [
            'items'      => $items,
            'filters'    => $request->only(['search', 'category', 'limit']),
            'categories' => ItemCategory::all(),
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
            'item_category_id' => 'required|exists:item_categories,id',
            'unit_id' => 'required|exists:units,id',
        ]);

        $count = Item::where('item_category_id', $validated['item_category_id'])->count() + 1;
        $categoryCode = str_pad($validated['item_category_id'], 3, '0', STR_PAD_LEFT);
        $itemCode = str_pad($count, 4, '0', STR_PAD_LEFT);
        $validated['sku'] = "CAT{$categoryCode}-{$itemCode}"; // Generate SKU

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('item', 'public');
        }

        Item::create($validated);
        return redirect()->route('item.index');
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item): Response
    {
        return Inertia::render('Inventory/Item/Detail', [
            'item' => $item->load(['category', 'unit', 'lastBalances']),
            'transactions' => InventoryTransaction::where('item_id', $item->id)->orderBy('transaction_date', 'desc')->orderBy('id', 'desc')->get(),
            'categories' => ItemCategory::all(),
            'units' => Unit::all(),
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
    public function update(Request $request, $id)
    {
        try {
            // $validated = $request->validate([
            //     'name' => 'required|string|max:255',
            //     'minimum_stock' => 'required|integer|min:0',
            //     'cost_price' => 'required|numeric|min:0',
            //     'selling_price' => 'required|numeric|min:0',
            //     'item_category_id' => 'required|exists:item_categories,id',
            //     'unit_id' => 'required|exists:units,id',
            // ]);

            $item = Item::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }
                $path = $request->file('image')->store('item', 'public');
                $item->image = $path;
            }

            $item->name = $request->name;
            $item->minimum_stock = $request->minimum_stock;
            $item->cost_price = $request->cost_price;
            $item->selling_price = $request->selling_price;
            $item->item_category_id = $request->item_category_id;
            $item->unit_id = $request->unit_id;
            $item->sku = $request->sku;
            $item->description = $request->description;
            $item->save();

            return Inertia::render('Inventory/Item/Detail', [
                'item' => $item->load(['category', 'unit']),
                'categories' => ItemCategory::all(),
                'units' => Unit::all(),
            ]);
            return redirect()->route('item.index');
        } catch (Exception $e) {
            return $e->getMessage();
        }
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

    public function search(Request $request)
    {
        $query = Item::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        return response()->json(
            $query->paginate(10) // Batasi 10 hasil per pencarian
        );
    }
}
