<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'account_id', 'debit', 'credit'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detail) {
            if (($detail->debit > 0 && $detail->credit > 0) || ($detail->debit === 0 && $detail->credit === 0)) {
                throw new \Exception('Only one of debit or credit can have a value.');
            }
        });
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
