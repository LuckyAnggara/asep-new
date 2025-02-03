<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter 'limit' dari query string, gunakan nilai default jika tidak ada
        $limit = $request->query('limit', 10); // Default adalah 10
        $search = $request->query('search', ''); // Default adalah string kosong
        $active = $request->query('active', ''); // Default adalah string kosong
        $gender = $request->query('gender', '');
        // Pastikan limit adalah angka yang valid dan dalam rentang tertentu
        $limit = is_numeric($limit) && $limit > 0 ? (int) $limit : 10;
        // Pastikan active adalah string yang valid
        // Ambil data members dengan pagination
        $members =
            Member::orderBy('name')
            ->when($active, function ($query, $active) {
                if ($active == 'all') {
                    return $query;
                } else if ($active == 'true') {
                    return $query->where('active', 1);
                } else {
                    return $query->where('active', 0);
                }
            })
            ->when($gender, function ($query, $gender) {
                if ($gender == 'all') {
                    return $query;
                } else
                    return $query->where('gender', $gender);
            })
            ->when($search, function ($query, $search) {
                return $query
                    ->whereAny([
                        'name',
                        'job_title',
                        'phone',
                        'member_id',
                        'department',
                        'address',
                    ], 'like', '%' . $search . '%');
            })->paginate($limit);

        return Inertia::render('Members/Index', [
            'members' => $members,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('members/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'member_id' => 'nullable|unique:members,member_id|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'join_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048', // Field foto bersifat opsional
        ]);

        $profilePicPath = null;
        // Periksa apakah ada file yang diunggah
        if ($request->hasFile('photo')) {
            // Simpan file ke folder 'profile_pics' di disk 'public'
            // $profilePicPath = Storage::putFile('photo', $request->file('photo'));
            $profilePicPath = $request->file('photo')->store('photo', 'public');
        } else {
            $profilePicPath = null; // Atur nilai default jika tidak ada file
        }

        // Simpan data member
        Member::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'job_title' => $request->job_title,
            'department' => $request->department,
            'join_date' => $request->join_date,
            'photo' => $profilePicPath, // Simpan path file
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()
            ->route('members.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return Inertia::render('Members/Edit', [
            'member' => $member
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'member_id' => 'required|unique:members,member_id,' . $member->id . '|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'join_date' => 'required|date',
        ]);

        $member->update($request->all());

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }
}
