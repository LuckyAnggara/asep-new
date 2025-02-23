<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// app/Models/Warehouse.php

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'location', 'description'];
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
