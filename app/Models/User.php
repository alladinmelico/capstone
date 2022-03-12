<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Interfaces\Notifiable as NotifiableInterface;

class User extends Authenticatable implements NotifiableInterface
{
    use SoftDeletes;

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'year',
        'section_id',
        'password',
        'google_id',
        'avatar',
        'avatar_original',
        'role_id',
        'course_id',
        'school_id',
        'verified_teacher',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public const ADMIN = 1;
    public const FACULTY = 2;
    public const STUDENT = 3;
    public const GUEST = 4;
    public const ROLES = [User::ADMIN, User::FACULTY, User::STUDENT, User::GUEST];

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_users')->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function temperatures()
    {
        return $this->hasMany(Temperature::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function rfid()
    {
        return $this->hasOne(Rfid::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class)->using(SectionUser::class);
    }

    /**
    * The channels the user receives notification broadcasts on.
    *
    * @return string
    */
    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.User.' . $this->id;
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
