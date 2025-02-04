<?php

namespace App\Http\Controllers;

use App\Models\Setoran;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SetoranController extends Controller
{
    public function index()
    {

        return Inertia::render('Accounting/FinancialStatements/CashFlow', [
            'setoran' => Setoran::with('anggota')->latest()->get(),
            // 'net_cash_flow' => $netCashFlow,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'ammount' => 'required|numeric|min:1000',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:4096'
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('setoran/attachment', 'public');
        }

        Setoran::create($validated);

        return redirect()
            ->route('members.index')
            ->with('success', 'Member created successfully.');
    }
}
