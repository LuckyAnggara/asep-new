<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'code',
        'normal'
    ];

    public function children()
    {
        return $this->hasMany(AccountSubCategory::class, 'category_id');
    }

    protected function normal(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
            set: fn(string $value) => strtolower($value),
        );
    }
}
