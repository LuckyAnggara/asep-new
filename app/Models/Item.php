<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'barcode',
        'item_category_id',
        'image',
        'variations',
        'cogs',
        'price',
        'min_stock',
        'weight',
        'unit',
        'description'
    ];

    protected $casts = [
        'variations' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }
}
