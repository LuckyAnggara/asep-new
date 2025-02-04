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

    public static function generateJournalCode()
    {
        $year = date('Y');
        $latestOB = self::where('reference', 'LIKE', "OB-$year-%")
            ->orderBy('reference', 'desc')
            ->first();

        $nextNumber = $latestOB ? (intval(substr($latestOB->reference, -3)) + 1) : 1;
        return "OB-$year-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }


    public static function generateUniqueJournalReference()
    {
        $year = date('Y');
        $latestOB = self::where('reference', 'LIKE', "UM-$year-%")
            ->orderBy('reference', 'desc')
            ->first();

        $nextNumber = $latestOB ? (intval(substr($latestOB->reference, -3)) + 1) : 1;
        return "UM-$year-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
