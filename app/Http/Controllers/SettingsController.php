<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        return inertia('Settings/Index', [
            'company' => Company::first(),
        ]);
    }

    public function updateCompany(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        $company = Company::findOrFail($id);
        // Handle file attachment
        $logoPath = $company->logo; // Simpan path lama sebagai fallback
        if ($request->hasFile('logo')) {
            // Hapus file lama jika ada
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            // Simpan file baru
            $logoPath = $request->file('logo')->store('logo', 'public');
            $company->logo = $logoPath;
            $company->save();
        }

        $company->update($request->except('logo'));

        return redirect()->back()->with('success', 'Company settings updated.');
    }

    public function updatePreferences(Request $request, $id)
    {
        $request->validate([
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            // 'theme' => 'required|string|in:light,dark,auto',
        ]);

        $company = Company::findOrFail($id);
        $company->update($request->only(['currency', 'timezone']));

        return redirect()->back()->with('success', 'Preferences updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        // Update password
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);


        return back()->with('success', 'Password updated successfully.');
    }
}
