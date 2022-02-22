<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'department_id'
    ];

    protected $appends = ['department'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getDepartmentAttribute()
    {
        return Config::get('constants.departments.' . $this->department_id);
    }
}
