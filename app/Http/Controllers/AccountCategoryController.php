<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', ''); // Default adalah string kosong

        $category = AccountCategory::when($search, function ($query, $search) {
            return $query
                ->whereAny(
                    [
                        'name',
                        'code',
                        'description',
                    ],
                    'like',
                    '%' . $search . '%'
                );
        })->get();


        return Inertia::render('ChartOfAccounts/Index', [
            'category' => $category,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('ChartOfAccounts/Create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:account_categories,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        AccountCategory::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('chart-of-accounts.index')->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $account = AccountCategory::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountCategory $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:account_categories,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        // $account->update($validated);

        $category = AccountCategory::findOrFail($id);

        $category->code = $request->code;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return redirect()->route('chart-of-accounts.index')->with('success', 'Account created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $account = AccountCategory::findOrFail($id);

        if ($account->children()->exists()) {
            return redirect(route('chart-of-accounts.index'))->withErrors('error', 'Cannot delete an account with children.');
        }
        $account->delete();
        return redirect(route('chart-of-accounts.index'))->with('success', 'Cannot delete an account with children.');
    }
}
