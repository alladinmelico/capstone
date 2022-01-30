<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        'president_id',
        'faculty_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->using(SectionUser::class);
    }

    public function president()
    {
        return $this->belongsTo(User::class, 'president_id');
    }

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    public function getPresidentNameAttribute()
    {
        return $this->president->name;
    }

    public function getFacultyNameAttribute()
    {
        return $this->faculty->name;
    }
}
