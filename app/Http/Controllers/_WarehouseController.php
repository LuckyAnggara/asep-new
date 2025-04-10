<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WarehouseController extends Controller
{
    public function index(): Response
    {
        $warehouses = Warehouse::latest()->get();
        return Inertia::render('Warehouses/Index', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:warehouses,name',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Warehouse::create($request->all());

        return redirect()->route('warehouse.index')->with('success', 'Warehouse added successfully!');
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|unique:warehouses,name,' . $warehouse->id,
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $warehouse->update($request->all());

        return redirect()->route('warehouse.index')->with('success', 'Warehouse updated successfully!');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouse.index')->with('success', 'Warehouse deleted successfully!');
    }
}
