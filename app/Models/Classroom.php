<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_classroom_id',
        'subject_id',
        'schedule_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'classroom_users')->withTimestamps();
    }

    public function subject()
    {
        return $this->hasMany(Subject::class);
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }

}