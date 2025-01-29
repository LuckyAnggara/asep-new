<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with('details.account')->get();
        return inertia('Transactions/Index', ['transactions' => $transactions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
            'details' => 'required|array',
            'details.*.account_id' => 'required|exists:accounts,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
        ]);

        $transaction = Transaction::create($request->only('transaction_date', 'description', 'created_by'));

        foreach ($request->details as $detail) {
            $transaction->details()->create($detail);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transactions = Transaction::with('details.account')->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
            'details' => 'required|array',
            'details.*.account_id' => 'required|exists:accounts,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
        ]);

        $transaction->update($request->only('transaction_date', 'description'));

        $transaction->details()->delete();

        foreach ($request->details as $detail) {
            $transaction->details()->create($detail);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->details()->delete();
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
