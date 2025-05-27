<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'name' => 'Test User',
            'email' => 'test@cinema-app.com',
            'password' => bcrypt('AdminCinema999'),
        ]);

        User::create([
            'name' => 'Test User 2',
            'email' => 'test2@cinema-app.com',
            'password' => bcrypt('AdminCinema999'),
        ]);

        $this->call([
            CinemaSeeder::class,
        ]);

    }
}
