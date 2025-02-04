<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = ['sub_category_id', 'code', 'name', 'account_number', 'description', 'cashflow_type'];

    protected $casts = [
        'id' => 'string',
        'sub_category_id' => 'string'
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucwords($value),
            set: fn(string $value) => strtolower($value),
        );
    }

    public function children()
    {
        return $this->hasMany(JournalEntryDetail::class, 'chart_of_accounts_id');
    }

    public function parent()
    {
        return $this->belongsTo(AccountSubCategory::class, 'sub_category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(AccountSubCategory::class, 'sub_category_id');
    }

    public function journalEntryDetail()
    {
        return $this->belongsTo(JournalEntryDetail::class, 'chart_of_accounts_id');
    }
}
