<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notes')->insert([
          'title' =>'Smooth flight',
          'body' => 'This was a lovely smooth flight',
          'user_id' => 1,
          'flight_id' => 1,
        ]);

        DB::table('notes')->insert([
          'title' =>'Short trip',
          'body' => 'No food on this flight',
          'user_id' => 1,
          'flight_id' => 2,
        ]);

        DB::table('notes')->insert([
          'title' =>'Long flight',
          'body' => 'Cheesecake was lovely',
          'user_id' => 2,
          'flight_id' => 3,
        ]);


    }
}
