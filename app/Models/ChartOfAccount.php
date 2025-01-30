<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = ['sub_category_id', 'code', 'name', 'account_number', 'description'];

    protected $casts = [
        'sub_category_id' => 'string',
        'id' => 'string'
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucwords($value),
            set: fn(string $value) => strtolower($value),
        );
    }

    public function parent()
    {
        return $this->belongsTo(AccountSubCategory::class, 'sub_category_id');
    }
}
