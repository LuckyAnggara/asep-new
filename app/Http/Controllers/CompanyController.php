<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        return inertia('Settings/Index', [
            'company' => Company::first(),
        ]);
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'registration_number' => 'nullable|string|max:50',
            'currency' => 'required|string|max:10',
            'decimal' => 'required|integer|max:1',
            'timezone' => 'required|string|max:50',
            'theme' => 'required|string|in:light,dark',
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::delete($company->logo);
            }
            $path = $request->file('logo')->store('company');
            $company->logo = $path;
        }

        $company->update($request->except('logo'));

        return redirect()->back()->with('success', 'Identitas perusahaan diperbarui.');
    }

    public function updateCompany(Request $request, Company $company)
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

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::delete($company->logo);
            }
            $path = $request->file('logo')->store('company');
            $company->logo = $path;
        }

        $company->update($request->except('logo'));

        return redirect()->back()->with('success', 'Company settings updated.');
    }

    public function updatePreferences(Request $request, Company $company)
    {
        $request->validate([
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            'decimal' => 'required|integer|max:1',
        ]);

        $company->update($request->only(['currency', 'timezone', 'decimal']));

        return redirect()->back()->with('success', 'Preferences updated.');
    }
}
