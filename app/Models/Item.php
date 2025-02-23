<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['sku', 'name', 'image', 'minimum_stock', 'cost_price', 'selling_price', 'description', 'category_id', 'unit_id'];
    protected $casts = [
        'created_at' =>
        'datetime:d F Y',
    ];
    public function category()
    {
        return $this->belongsTo(ItemCategory::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
