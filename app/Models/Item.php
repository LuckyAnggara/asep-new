<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['sku', 'name', 'image', 'minimum_stock', 'cost_price', 'selling_price', 'description', 'category_id', 'unit_id'];
    protected $casts = [
        'selling_price' => 'double',
        'cost_price' => 'double',
        'created_at' =>
        'datetime:d F Y',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class)->orderBy('id', 'desc');
    }

    public function lastBalances()
    {
        return $this->hasOne(LastBalance::class, 'item_id')->withDefault([
            'last_balance' => 0
        ]);
    }
}
