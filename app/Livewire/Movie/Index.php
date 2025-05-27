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
        Log::info("Getting listeners for showtimeId: " . $this->showtimeId);
        if ($this->showtimeId) {
            Log::info("Listening to channel: showtime-seat.{$this->showtimeId}");
            return [
                // "echo:showtime-seat.{$this->showtimeId},SeatBooked" => 'handleSeatBooked',
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
        Log::info("Movie selected: " . $movieId);
        $this->selectedMovie = Movie::with(['showtimes.hall', 'showtimes.showtimeSeats'])->find($movieId);
        $this->showtimes = $this->selectedMovie->showtimes;
        $this->selectedShowtime = null;
        $this->selectedShowtimeSeats = [];
    }

    public function selectShowtime($showtimeId)
    {
        Log::info("Showtime selected: " . $showtimeId);
        $this->showtimeId = $showtimeId;

        // Refresh showtime to get fresh seats
        $this->selectedShowtime = $this->selectedMovie->showtimes->firstWhere('id', $showtimeId);
        $this->seats = $this->selectedShowtime->showtimeSeats;
        $this->selectedShowtimeSeats = [];

        // Force Livewire to re-register listeners
        // $this->dispatch('$refresh');
    }

    public function resetListeners()
    {
        Log::info("Resetting listeners");
        $this->dispatch('$refresh'); // triggers re-render (fine)
    }

    public function selectSeat($seatId)
    {
        Log::info("Seat selected: " . $seatId);
        $seat = ShowtimeSeat::where('seat_id', $seatId)
            ->where('showtime_id', $this->showtimeId)
            ->first();

        if (!$seat || !$seat->is_available) {
            return;
        }

        $seat->update(['is_available' => false]);

        // Broadcast to others
        // event(new SeatBooked($seat));

        try {
            Log::info("Emitting event for seat: " . $seatId);
            broadcast(new SeatBooked($seat))->toOthers();
            event(new \App\Events\SeatBooked($seat));
            Log::info("Event emitted successfully");
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
