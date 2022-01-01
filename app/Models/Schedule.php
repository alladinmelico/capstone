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
        'end_date',
        'start_date',
        'day',
        'valid_until',
        'note',
        'facility_id',
        'is_recurring',
        'days_of_week',
        'repeat_by',
        'type',
        'title',
        'user_id',
        'classroom_id',
    ];

    protected $casts = [
        'days_of_week' => 'json',
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

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
