<?php

namespace Database\Seeders;

use App\Models\Hall;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\ShowtimeSeat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CinemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('movies')->truncate();
        DB::table('halls')->truncate();
        DB::table('seats')->truncate();
        DB::table('showtimes')->truncate();
        DB::table('showtime_seats')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // Create movies
        $movies = Movie::factory(10)->create();

        // Create halls
        $halls = Hall::factory(5)->create();


        // Create seats for each hall
        foreach ($halls as $hall) {

            for ($row = 1; $row <= $hall->rows; $row++) {
                for ($col = 1; $col <= $hall->columns; $col++) {
                    Seat::create([
                        'hall_id' => $hall->id,
                        'seat_number' => chr(64 + $row) . $col, // e.g., A1, B2, etc.
                    ]);
                }
            }
        }

        // Create showtimes for movies
        foreach ($movies as $movie) {

            $showtime = Showtime::create([
                'movie_id' => $movie->id,
                'hall_id' => $halls->random()->id, // Randomly assign a hall
                'date' => now()->addDays(rand(1, 30)), // Random date within the next 30 days
                'start_time' => now()->addHours(rand(11, 23)), // Random start time
                'price' => rand(10, 50), // Random price between 10 and 50
            ]);

            // Create showtime seats for each showtime
            foreach ($showtime->hall->seats as $seat) {
                ShowtimeSeat::create([
                    'showtime_id' => $showtime->id,
                    'seat_id' => $seat->id,
                ]);
            }
        }
    }
}
