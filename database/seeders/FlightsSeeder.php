<?php

namespace Database\Seeders;

use App\Models\Flights;
use Faker\Factory;
use Illuminate\Database\Seeder;

class FlightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        Flights::create([
            'departure_date' => $faker->date,
            'flight_number' => 'FR9668',
            'departure_airport' => 'SZG',
            'arrival_airport' => 'MAN',
            'distance' => 771,
            'airline' => 'RYR',
        ]);

        Flights::create([
            'departure_date' => $faker->date,
            'flight_number' => 'FR9667',
            'departure_airport' => 'MAN',
            'arrival_airport' => 'SZG',
            'distance' => 771,
            'airline' => 'RYR',
        ]);

        Flights::create([
            'departure_date' => $faker->date,
            'flight_number' => 'CX219',
            'departure_airport' => 'HKG',
            'arrival_airport' => 'MAN',
            'distance' => 5984,
            'airline' => 'CPA',
        ]);

        Flights::create([
            'departure_date' => $faker->date,
            'flight_number' => 'CX561',
            'departure_airport' => 'KIX',
            'arrival_airport' => 'HKG',
            'distance' => 1540,
            'airline' => 'CPA',
        ]);
    }
}
