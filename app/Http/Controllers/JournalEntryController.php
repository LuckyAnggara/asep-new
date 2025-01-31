<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class JournalEntryController extends Controller
{

    public function index(Request $request)
    {
        $limit = $request->query('limit', 1000000);
        $search = $request->query('search', ''); // Default adalah string kosong
        $year = $request->query('year', Carbon::now()->format('Y')); // Default adalah string kosong
        $month = $request->query('month', ''); // Default adalah string kosong
        $date = $request->query('date', ''); // Default adalah string kosong

        $journalEntries = JournalEntry::whereYear('date', $year)->with('details')->when($search, function ($query, $search) {
            return $query
                ->whereAny(
                    [
                        'reference',
                        'description',
                    ],
                    'like',
                    '%' . $search . '%'
                );
        })->when($month, function ($query, $month) {
            if ($month == 'all') {
                return $query;
            }
            return $query
                ->whereMonth('date', $month);
        })->when($date, function ($query, $date) {
            $date =
                Carbon::parse($date)->toDateString();
            if ($date == '') {
                return $query;
            }

            return $query
                ->whereDate('date', $date);
        })->orderBy('date', 'desc')->paginate($limit);

        return Inertia::render('accounting/journal/Index', [
            'journal_entries' => $journalEntries
        ]);
    }

    public function create()
    {
        return Inertia::render('accounting/journal/Create', [
            'accounts' => ChartOfAccount::all()
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'reference' => 'nullable|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:4096',
            'details' => 'required|array|min:1',
            'details.*.chart_of_accounts_id' => 'required|exists:chart_of_accounts,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
        ]);


        // Upload file jika ada
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('journal_attachments', 'public');
        }

        // Simpan data Journal Entry
        $journalEntry = JournalEntry::create([
            'reference' => $request->reference,
            'date' => Carbon::parse($request->date)->toDateString(),
            'description' => $request->description,
            'attachment' => $attachmentPath,
        ]);

        // Simpan details
        foreach ($request->details as $detail) {
            JournalEntryDetail::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_of_accounts_id' => $detail['chart_of_accounts_id'],
                'debit' => $detail['debit'],
                'credit' => $detail['credit'],
            ]);
        }

        return redirect()->route('journal-entries.index')->with('success', 'Journal Entry berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $journalEntry = JournalEntry::with('details')->where('id', $id)->first();
        return Inertia::render('accounting/journal/Edit', [
            'journal_entry' => $journalEntry,
            'accounts' => ChartOfAccount::all()
        ]);
    }

    public function update(Request $request, $id)
    {

        // Validasi input
        $request->validate([
            'reference' => 'nullable|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:4096',
            'details' => 'required|array|min:1',
            'details.*.chart_of_accounts_id' => 'required|exists:chart_of_accounts,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
        ]);

        // Temukan journal entry yang akan diupdate
        $journalEntry = JournalEntry::findOrFail($id);

        // Handle file attachment
        $attachmentPath = $journalEntry->attachment; // Simpan path lama sebagai fallback
        if ($request->hasFile('attachment')) {
            // Hapus file lama jika ada
            if ($journalEntry->attachment) {
                Storage::disk('public')->delete($journalEntry->attachment);
            }
            // Simpan file baru
            $attachmentPath = $request->file('attachment')->store('journal_attachments', 'public');
        }

        // Update data Journal Entry
        $journalEntry->update([
            'reference' => $request->reference,
            'date' => Carbon::parse($request->date)->toDateString(),
            'description' => $request->description,
            'attachment' => $attachmentPath,
        ]);

        // Hapus details lama
        $journalEntry->details()->delete();

        // Simpan details baru
        foreach ($request->details as $detail) {
            JournalEntryDetail::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_of_accounts_id' => $detail['chart_of_accounts_id'],
                'debit' => $detail['debit'],
                'credit' => $detail['credit'],
            ]);
        }

        return redirect()->route('journal-entries.index')->with('success', 'Journal Entry berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $journalEntry = JournalEntry::findOrFail($id);

        // Hapus file jika ada
        if ($journalEntry->attachment) {
            Storage::disk('public')->delete($journalEntry->attachment);
        }

        // Hapus details terlebih dahulu
        $journalEntry->details()->delete();

        // Hapus Journal Entry
        $journalEntry->delete();

        return redirect()->route('journal-entries.index')->with('success', 'Journal Entry berhasil dihapus.');
    }
}
