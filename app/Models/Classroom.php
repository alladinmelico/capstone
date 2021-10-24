<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $fillable = [
        'google_classroom_id',
        'name',
        'description_heading',
        'description',
        'section',
        'invite_code',
        'subject_id',
        'schedule_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'classroom_users')->withTimestamps();
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

}