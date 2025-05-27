<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingShowtimeSeat extends Model
{
    protected $fillable = [
        'booking_id',
        'showtime_seat_id',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function showtimeSeat()
    {
        return $this->belongsTo(ShowtimeSeat::class);
    }
}
