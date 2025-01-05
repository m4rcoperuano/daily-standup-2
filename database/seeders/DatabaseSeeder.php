<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Team::query()
            ->whereDoesntHave('pointingRooms')
            ->lazy()
            ->each(function (Team $team) {
                $team->pointingRooms()->create([
                    'name' => 'Default Room',
                ]);
            });
    }
}
