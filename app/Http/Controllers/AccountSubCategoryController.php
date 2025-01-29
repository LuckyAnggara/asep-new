<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use App\Models\AccountSubCategory;
use Illuminate\Http\Request;

class AccountSubCategoryController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:account_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $lastCode = AccountSubCategory::where('category_id', $request->category_id)
            ->orderBy('code', 'desc')
            ->first();
        $categoryCode = AccountCategory::where('id', $request->category_id)->first();

        // Tentukan kode baru
        $newCode = $lastCode ? intval(explode('-', $lastCode->code)[1]) + 1 : 1;
        $code = $categoryCode->code . '-' . str_pad($newCode, 2, '0', STR_PAD_LEFT);

        // Simpan data baru
        AccountSubCategory::create([
            'category_id' => $request->category_id,
            'code' => $code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('chart-of-accounts.index')->with('success', 'Sub Category Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountSubCategory $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:account_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Cari data sub-kategori berdasarkan ID
        $subCategory = AccountSubCategory::findOrFail($id);

        // Jika `category_id` diubah, maka perlu hitung ulang kode
        if ($subCategory->category_id != $request->category_id) {
            // Cari kode terakhir berdasarkan `category_id` baru
            $lastCode = AccountSubCategory::where('category_id', $request->category_id)
                ->orderBy('code', 'desc')
                ->first();

            //cari kodefikasi awal
            $categoryCode = AccountCategory::where('id', $request->category_id)->first();

            // Tentukan kode baru
            $newCode = $lastCode ? intval(explode('-', $lastCode->code)[1]) + 1 : 1;
            $code = $categoryCode->code . '-' . str_pad($newCode, 2, '0', STR_PAD_LEFT);

            // Perbarui kode
            $subCategory->code = $code;
        }

        // Perbarui data lainnya
        $subCategory->category_id = $request->category_id;
        $subCategory->name = $request->name;
        $subCategory->description = $request->description;

        $subCategory->save();

        return redirect()->route('chart-of-accounts.index')->with('success', 'Account created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $account = AccountSubCategory::findOrFail($id);

        if ($account->children()->exists()) {
            return redirect(route('chart-of-accounts.index'))->withErrors('error', 'Cannot delete an account with children.');
        }

        $account->delete();
        return redirect(route('chart-of-accounts.index'))->with('success', 'Cannot delete an account with children.');
    }
}
