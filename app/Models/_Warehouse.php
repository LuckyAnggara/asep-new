<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// app/Models/Warehouse.php

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location'];

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function lastBalances()
    {
        return $this->hasMany(LastBalance::class);
    }
}
