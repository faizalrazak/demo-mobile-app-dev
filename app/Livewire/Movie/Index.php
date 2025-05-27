<?php

namespace App\Livewire\Movie;

use App\Events\SeatBooked;
use App\Models\Movie;
use App\Models\ShowtimeSeat;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Index extends Component
{
    public $movies;
    public $selectedMovie;
    public $showtimes;
    public $selectedShowtime;
    public $showtimeId;
    public $seats;
    public $selectedShowtimeSeats = [];

    public function mount()
    {
        $this->movies = Movie::with(['showtimes.hall', 'showtimes.showtimeSeats'])->get();
    }

    public function getListeners()
    {
        if ($this->showtimeId) {
            return [
                "echo-channel:showtime-seat.{$this->showtimeId},seat.booked" => 'handleSeatBooked',

            ];
        }

        return [];
    }

    public function render()
    {
        return view('livewire.movie.index');
    }

    public function selectMovie($movieId)
    {
        $this->selectedMovie = Movie::with(['showtimes.hall', 'showtimes.showtimeSeats'])->find($movieId);
        $this->showtimes = $this->selectedMovie->showtimes;
        $this->selectedShowtime = null;
        $this->selectedShowtimeSeats = [];
    }

    public function selectShowtime($showtimeId)
    {
        $this->showtimeId = $showtimeId;

        // Refresh showtime to get fresh seats
        $this->selectedShowtime = $this->selectedMovie->showtimes->firstWhere('id', $showtimeId);
        $this->seats = $this->selectedShowtime->showtimeSeats;
        $this->selectedShowtimeSeats = [];

    }

    public function resetListeners()
    {
        $this->dispatch('$refresh'); // triggers re-render (fine)
    }

    public function selectSeat($seatId)
    {
        $seat = ShowtimeSeat::where('seat_id', $seatId)
            ->where('showtime_id', $this->showtimeId)
            ->first();

        if (!$seat || !$seat->is_available) {
            return;
        }

        $seat->update(['is_available' => false]);

        try {
            broadcast(new SeatBooked($seat))->toOthers();
        } catch (\Exception $e) {
            Log::error("Error emitting event: " . $e->getMessage());
        }

        // Update local state
        $this->seats = ShowtimeSeat::where('showtime_id', $this->showtimeId)->get();
    }

    public function handleSeatBooked($seat_id, $is_available)
    {
        $this->seats = $this->seats->map(function ($seat) use ($seat_id, $is_available) {
            if ($seat->seat_id == $seat_id) {
                $seat->is_available = $is_available;
            }
            return $seat;
        });
    }

}
