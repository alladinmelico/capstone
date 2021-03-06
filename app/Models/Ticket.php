<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TicketStatus;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'ticket_id',
        'priority',
        'user_id',
        'message',
        'status',
        'category',
    ];

    protected $casts = [
        'priority' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute() {
        return ucfirst(strtolower(TicketStatus::fromValue($this->status)->key));
    }
}
