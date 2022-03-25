<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'department_id',
        'cover',
    ];

    protected $appends = ['department'];

    protected $casts = [
        'cover' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getDepartmentAttribute()
    {
        return Config::get('constants.departments.' . $this->department_id);
    }
}
