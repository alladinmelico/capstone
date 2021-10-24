<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $fillable = [
        'start_at',
        'end_at',
        'day',
        'valid_until',
        'note',
        'facility_id',
        'user_id',
    ];

    public function getStartAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s');
    }

    public function getEndAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}