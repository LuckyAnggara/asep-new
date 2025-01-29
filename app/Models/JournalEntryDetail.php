<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntryDetail extends Model
{
    use HasFactory;

    protected $fillable = ['journal_entry_id', 'account_id', 'debit', 'credit'];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }
}
