<?php

namespace App\Events;

use App\Models\ShowtimeSeat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SeatBooked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $showtimeSeat;

    public function __construct(ShowtimeSeat $showtimeSeat)
    {
        $this->showtimeSeat = $showtimeSeat;
    }

    public function broadcastOn()
    {
        // Logging here instead of dump to avoid serialization issues
        Log::info("Broadcasting on channel: showtime-seat." . $this->showtimeSeat->showtime_id);
        return new Channel('showtime-seat.' . $this->showtimeSeat->showtime_id);
    }

    public function broadcastWith()
    {
        Log::info("Broadcasting data: ", [
            'seat_id' => $this->showtimeSeat->seat_id,
            'is_available' => $this->showtimeSeat->is_available,
        ]);
        return [
            'seat_id' => $this->showtimeSeat->seat_id,
            'is_available' => $this->showtimeSeat->is_available,
        ];
    }

    public function broadcastAs()
    {
        return 'seat.booked';
    }


}
