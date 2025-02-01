<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10000); // Default adalah 10
        $search = $request->query('search', ''); // Default adalah string kosong
        $type = $request->query('type', ''); // Default adalah string kosong
        // Pastikan limit adalah angka yang valid dan

        $accounts = Account::with('parent')->when($type, function ($query, $type) {
            if ($type == 'all') {
                return $query;
            }
            return $query
                ->where(
                    'type',
                    $type
                );
        })->when($search, function ($query, $search) {
            return $query
                ->whereAny(
                    [
                        'name',
                        'account_code',
                        'type',
                    ],
                    'like',
                    '%' . $search . '%'
                );
        })->paginate($limit);
        return Inertia::render('Accounts/Index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Accounts/Create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_code' => 'required|string|max:10|unique:accounts,account_code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'parent_id' => 'nullable|exists:accounts,id',
        ]);

        Account::create($request->only('account_code', 'name', 'type', 'parent_id',));

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'account_code' => 'required|string|max:10|unique:accounts,account_code,' . $account->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'parent_id' => 'nullable|exists:accounts,id',
        ]);

        $account->update($request->only('account_code', 'name', 'type', 'parent_id'));

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        if ($account->children()->exists()) {
            return redirect()->route('accounts.index')->with('error', 'Cannot delete an account with children.');
        }

        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
    }
}
