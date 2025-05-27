<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShowtimeSeat extends Model
{
    protected $fillable = [
        'showtime_id',
        'seat_id',
        'is_available',
    ];

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
