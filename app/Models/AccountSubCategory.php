<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountSubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'code',
    ];

    protected $casts = [
        'category_id' => 'string'
    ];

    public function children()
    {
        return $this->hasMany(ChartOfAccount::class, 'sub_category_id');
    }

    public function coa()
    {
        return $this->hasMany(ChartOfAccount::class, 'sub_category_id');
    }

    public function category()
    {
        return $this->belongsTo(AccountCategory::class, 'category_id');
    }
}
