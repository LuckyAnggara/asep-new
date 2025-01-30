<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntryDetail extends Model
{
    use HasFactory;

    protected $fillable = ['journal_entry_id', 'chart_of_accounts_id', 'debit', 'credit'];
    protected $casts = [
        'chart_of_accounts_id' => 'string',
        'debit' => 'double',
        'credit' => 'double',
    ];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }
}
