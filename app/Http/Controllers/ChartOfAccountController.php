<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\AccountSubCategory;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10000); // Default adalah 10
        $search = $request->query('search', ''); // Default adalah string kosong
        $catFilter = $request->query('category', ''); // Default adalah string kosong
        $sub_category = $request->query('sub_category', ''); // Default adalah string kosong
        // Pastikan limit adalah angka yang valid dan

        $accounts = ChartOfAccount::with('parent.category')->when($catFilter, function ($query, $catFilter) {
            if ($catFilter == 'all') {
                return $query;
            }

            return $query
                ->whereHas('parent', function ($q) use ($catFilter) {
                    $q->where('category_id', $catFilter);
                });
        })->when($sub_category, function ($query, $sub_category) {
            if ($sub_category == 'all') {
                return $query;
            }

            return $query
                ->where('sub_category_id', $sub_category);
        })->when($search, function ($query, $search) {
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
        })->paginate($limit);

        $category = AccountCategory::all();
        $sub_category = AccountSubCategory::with('category')->get();

        return Inertia::render('chartofaccounts/Index', [
            'accounts' => $accounts,
            'category' => $category,
            'sub_category' => $sub_category,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('chartofaccounts/Create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:account_sub_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $lastCode = ChartOfAccount::where('sub_category_id', $request->sub_category_id)
            ->orderBy('code', 'desc')
            ->first();
        $subCategoryCode = AccountSubCategory::where('id', $request->sub_category_id)->first();

        // Tentukan kode baru
        $newCode = $lastCode ? intval(explode('-', $lastCode->code)[2]) + 1 : 1;
        $code = $subCategoryCode->code . '-' . str_pad($newCode, 2, '0', STR_PAD_LEFT);

        // Simpan data baru
        ChartOfAccount::create([
            'sub_category_id' => $request->sub_category_id,
            'code' => $code,
            'name' => $request->name,
            'account_number' => $newCode,
            'description' => $request->description,
        ]);

        return redirect()->route('chart-of-accounts.index')->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $account = ChartOfAccount::findOrFail($id);
        return $account;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChartOfAccount $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'sub_category_id' => 'required|exists:account_sub_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Cari data akun berdasarkan ID
        $account = ChartOfAccount::findOrFail($id);

        // Jika `category_id` diubah, maka perlu hitung ulang kode
        if ($account->sub_category_id != $request->sub_category_id) {
            // Cari kode terakhir berdasarkan `category_id` baru
            $lastCode = ChartOfAccount::where('sub_category_id', $request->sub_category_id)
                ->orderBy('code', 'desc')
                ->first();

            //cari kodefikasi awal
            $subCategoryCode = AccountSubCategory::where('id', $request->sub_category_id)->first();

            // Tentukan kode baru
            $newCode = $lastCode ? intval(explode('-', $lastCode->code)[1]) + 1 : 1;
            $code = $subCategoryCode->code . '-' . str_pad($newCode, 2, '0', STR_PAD_LEFT);

            // Perbarui kode
            $account->code = $code;
        }

        // Perbarui data lainnya
        $account->sub_category_id = $request->sub_category_id;
        $account->name = $request->name;
        $account->description = $request->description;

        $account->save();

        return redirect()->route('chart-of-accounts.index')->with('success', 'Account created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $account = ChartOfAccount::findOrFail($id);

        if ($account->children()->exists()) {
            return redirect(route('chart-of-accounts.index'))->withErrors('error', 'Cannot delete an account with children.');
        }
        $account->delete();
        return redirect(route('chart-of-accounts.index'))->with('success', 'Cannot delete an account with children.');
    }
}
