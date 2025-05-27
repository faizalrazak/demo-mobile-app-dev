<div>
    <h2 class="text-2xl font-bold mb-4">Movies {{ $showtimeId ?? 0 }}</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 pb-4">
        @foreach($movies as $movie)
            <div class="bg-white rounded-lg shadow-lg flex flex-col items-center">
                <div class="relative group w-full">
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-60 object-cover rounded-t-lg transition-transform group-hover:scale-105">
                    <div class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 flex flex-col justify-end p-3 rounded-t-lg transition-opacity">
                        <button wire:click="selectMovie({{ $movie->id }})" class="text-white text-lg font-semibold hover:underline">
                            {{ $movie->title }}
                        </button>
                        <div class="text-gray-300 text-xs mt-1">
                            {{ $movie->release_year ?? '' }}
                        </div>
                    </div>
                </div>
                <div class="p-2 w-full text-center">
                    <button wire:click="selectMovie({{ $movie->id }})" class="text-gray-900 font-semibold hover:underline">
                        {{ $movie->title }}
                    </button>
                </div>
                @if($selectedMovie && $selectedMovie->id === $movie->id)
                    <ul class="mt-2 bg-white rounded shadow p-2 w-full">
                        @foreach($showtimes as $showtime)
                            <li class="mb-1">
                                <button wire:click="selectShowtime({{ $showtime->id }})" class="text-green-600 hover:underline text-sm">
                                    {{ date('d/M/Y', strtotime($showtime->date)) }} {{ date('H:i', strtotime($showtime->start_time)) }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    </div>

    @if($selectedShowtime)
        <div class="mt-6 p-4 border rounded">
            <h3 class="text-xl font-semibold mb-2">Book Your Seat for {{ $selectedMovie->title }}</h3>
            {{-- Booking seat UI goes here --}}
            <div class="grid grid-cols-12 gap-2">
                @foreach($seats as $seat)
                    <div wire:click='selectSeat({{ $seat->seat_id }})' class="border rounded p-2 text-center {{ !$seat->is_available ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-green-100 hover:bg-green-200 cursor-pointer' }}">
                        <span>{{ $seat->seat->seat_number }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
(() => {
    const showtimeId = @json($showtimeId);

    document.addEventListener('DOMContentLoaded', function () {
        function waitForEcho(callback) {
            if (window.Echo && window.Echo.connector && window.Echo.connector.socket) {
                callback();
            } else {
                setTimeout(() => waitForEcho(callback), 100);
            }
        }

        waitForEcho(() => {

            console.log('Echo is loaded:', !!window.Echo);
            window.Echo.channel(`showtime-seat.${showtimeId}`)
                .listen('seat.booked', (e) => {
                    // e.seat_id, e.is_available
                    Livewire.emit('handleSeatBooked', e.seat_id, e.is_available);
                });

            window.Echo.connector.socket.addEventListener('open', () => {
                console.log('Connected to WebSocket');
            });
            window.Echo.connector.socket.addEventListener('error', (event) => {
                console.error('WebSocket error', event);
            });
        });
    });
})();
</script>
