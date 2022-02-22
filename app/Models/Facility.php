<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Facility extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capacity',
        'building_id',
        'type',
        'department_id',
        'svg_key'
    ];

    protected $appends = ['department'];

    public function getTypeAttribute($value)
    {
        return config('constants.facilities.types.' . $value);
    }

    public function getDepartmentAttribute()
    {
        return Config::get('constants.departments.' . $this->department_id);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
