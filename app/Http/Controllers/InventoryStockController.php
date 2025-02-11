<?php

namespace App\Http\Controllers;

use App\Models\InventoryStock;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryStockController extends Controller
{
    public function index(): Response
    {
        $warehouses = Warehouse::with('stocks.item')->get();
        $items = Item::all();

        return Inertia::render('Inventory/Index', compact('warehouses', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $stock = InventoryStock::firstOrNew([
            'warehouse_id' => $request->warehouse_id,
            'item_id' => $request->item_id,
        ]);

        $stock->quantity += $request->quantity;
        $stock->save();

        return redirect()->route('inventory.index')->with('success', 'Stock updated!');
    }

    public function decreaseStock(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $stock = InventoryStock::where([
            'warehouse_id' => $request->warehouse_id,
            'item_id' => $request->item_id,
        ])->first();

        if (!$stock || $stock->quantity < $request->quantity) {
            return redirect()->back()->withErrors(['error' => 'Insufficient stock!']);
        }

        $stock->quantity -= $request->quantity;
        $stock->save();

        return redirect()->route('inventory.index')->with('success', 'Stock reduced!');
    }
}
