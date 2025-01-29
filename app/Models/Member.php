<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Member extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'job_title',
        'department',
        'join_date',
        'photo',
        'notes'
    ];

    protected $casts = [
        'date_of_birth' =>
        'datetime:d-m-Y',
        'join_date' =>
        'datetime:d-m-Y',
        'active' => 'boolean',
    ];

    protected $appends = ['full_image_path'];

    protected function fullImagePath(): Attribute
    {
        return new Attribute(
            get: fn() => asset($this->photo),
        );
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->member_id = self::generateMemberId();
        });
    }

    /**
     * Generate a unique member ID.
     *
     * @return string
     */
    private static function generateMemberId()
    {
        $date = now()->format('Ymd');
        $lastId = self::whereDate('created_at', today())->max('id');
        $sequence = $lastId ? $lastId + 1 : 1;

        return sprintf('MEM-%s-%04d', $date, $sequence);
    }


    public function scopeDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function getGenderAttribute($value)
    {
        return $value === 'male' ? 'Laki - Laki' : 'Perempuan';
    }
    // public function getActiveAttribute($value)
    // {
    //     // Jika nilai 1, return 'aktif', jika 0, return 'non aktif'
    //     return $value === 1 ? 'aktif' : 'non aktif';
    // }
}
