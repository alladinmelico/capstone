<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capacity',
        'type',
    ];

    public function getTypeAttribute($value)
    {
        return config('constants.facilities.types.' . $value);
    }
}