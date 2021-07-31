<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Temperature extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $fillable = [
        'temperature',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}