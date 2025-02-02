<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slogan',
        'logo',
        'address',
        'phone',
        'email',
        'website',
        'registration_number',
        'currency',
        'timezone',
        'theme'
    ];
}
