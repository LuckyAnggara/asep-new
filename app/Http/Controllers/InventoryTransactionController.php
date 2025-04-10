<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Item;
use App\Models\Warehouse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryTransactionController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $query = InventoryTransaction::with(['item'])->latest();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return Inertia::render('Inventory/Transaction/Index', [
            'transactions' =>  $query->paginate($limit)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/Transaction/Create', [
            'items' =>
            Inertia::lazy(fn() => Item::all()),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transactions' => 'required|array',
            'transactions.*.item_id' => 'required|exists:items,id',
            'transactions.*.type' => 'required|in:in,out',
            'transactions.*.item_id' => 'required|exists:items,id',
            'transactions.*.quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        if (!$request->reference) {
            $date = now()->format('Ymd'); // Format YYYYMMDD
            $count = InventoryTransaction::whereDate('transaction_date', now())->count() + 1;
            $reference = "TRX-{$date}-" . str_pad($count, 5, '0', STR_PAD_LEFT);
        } else {
            $reference = $request->reference;
        }

        foreach ($request->transactions as $detail) {
            InventoryTransaction::create(array_merge($detail, ['reference' => $reference, 'transaction_date' =>  Carbon::parse($request->transaction_date)->toDateString(), 'description' => $request->description]));
        }

        return redirect()->route('transaction.index');
    }

    public function destroy(InventoryTransaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transaction.index')->with('success', 'Transaction deleted.');
    }
}
