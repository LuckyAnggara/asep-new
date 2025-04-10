<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['reference', 'item_id', 'type', 'quantity',  'transaction_date', 'description'];
    protected $casts = [
        'transaction_date' =>
        'datetime:d F Y',
        'created_at' =>
        'datetime:d F Y',
    ];
    protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            $lastBalance = LastBalance::where('item_id', $transaction->item_id)->first();

            if ($lastBalance) {
                $lastBalance->last_balance += $transaction->type === 'in' ? $transaction->quantity : -$transaction->quantity;
                $lastBalance->save();
            } else {
                LastBalance::create([
                    'item_id' => $transaction->item_id,
                    'last_balance' => $transaction->type === 'in' ? $transaction->quantity : -$transaction->quantity,
                ]);
            }
        });
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
