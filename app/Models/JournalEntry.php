<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'description', 'reference', 'attachment'];

    protected $casts = [
        'date' =>
        'datetime:d F Y',
        'created_at' =>
        'datetime:d F Y',
    ];

    // Accessor untuk total nominal transaksi
    protected $appends = ['total_nominal'];

    public function details()
    {
        return $this->hasMany(JournalEntryDetail::class);
    }

    public function getTotalNominalAttribute()
    {
        return $this->details->sum(fn($detail) => $detail->debit);
    }
}
