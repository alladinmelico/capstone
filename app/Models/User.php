<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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
        'password',
        'google_id',
        'avatar',
        'avatar_original',
        'role_id',
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

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}