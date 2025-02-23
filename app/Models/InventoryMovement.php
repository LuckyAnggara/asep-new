<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'warehouse_id', 'quantity', 'movement_type'];
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
