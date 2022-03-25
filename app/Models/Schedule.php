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
        'note',
        'facility_id',
        'is_recurring',
        'days_of_week',
        'repeat_by',
        'type',
        'title',
        'user_id',
        'classroom_id',
        'attachment',
    ];

    protected $casts = [
        'days_of_week' => 'json',
    ];

    public function scopeFilterAccess($query)
    {
        $user = auth()->user();

        if ($user->role_id !== 1) {
            return $query->whereUserId($user->id);
        }
        return $query;
    }

    public function scopeHasScheduleNow($query)
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        return $query->whereDate('end_date', '>=', $date->toDateString())
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereTime('end_at', '>=', $time)
            ->whereTime('start_at', '<=', $time);
    }

    public function scopeHasScheduleToday($query)
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        return $query->whereDate('end_date', '>=', $date->toDateString())
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereTime('start_at', '<=', $time);
    }

    public function scopeOverstayNow($query)
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        return $query->whereDate('end_date', '>=', $date->toDateString())
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereTime('end_at', '<=', $time)
            ->whereTime('start_at', '<=', $time);
    }

    public function getIsValidAttribute()
    {
        $currDate = Carbon::now()->setTimezone(config('app.timezone'));
        $currTime = $currDate->format('H:i:s');
        return
        $currDate->betweenIncluded(Carbon::create($this->start_date), Carbon::create($this->end_date)) &&
        $currDate->betweenIncluded(Carbon::create($this->start_at)->format('H:i:s'), Carbon::create($this->end_at)->format('H:i:s')) &&
        $this->valid_recurring;
    }

    public function getStartAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s');
    }

    public function getEndAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s');
    }

    public function getIsRecurringAttribute($value)
    {
        return !!$value;
    }

    public function getDiffEndTimeAttribute() {
        $currDate = Carbon::now()->setTimezone(config('app.timezone'));
        $endTime = Carbon::parse($this->end_at);
        return [$endTime->diffInHours($currDate)];
    }

    public function getValidRecurringAttribute() {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        if ($this->is_recurring) {
            if ($this->repeat_by === 'daily') {
                return true;
            } else if ($this->repeat_by === 'monthly') {
                $startDate= Carbon::create($this->start_date);
                return $date->day === $startDate->day;
            } else {
                $daysOfWeek = $this->days_of_week;
                if (getType($daysOfWeek) == 'array') {
                    $daysOfWeek = implode(',', $daysOfWeek);
                }
                if (!str_contains($daysOfWeek, strtolower($date->englishDayOfWeek))) {
                    return false;
                }
                return true;
            }
        }
        return true;
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

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public static function hasSchedule($userId)
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');
        $batches = Batch::where('user_id', $userId)->get();

        return Schedule::where(function ($query) use($batches) {
                $query->whereIn('id', $batches->pluck('schedule_id'))->orWhere('user_id', $userId);
            })
            ->whereDate('end_date', '>=', $date)
            ->whereDate('start_date', '<=', $date)
            ->whereTime('end_at', '>=', $time)
            ->whereTime('start_at', '<=', $time)
            ->get()
            ->filter(function ($value, $key) use ($date) {
                return $value->valid_recurring;
            });
    }
}
