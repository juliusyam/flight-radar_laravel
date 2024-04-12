<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flights extends Model
{
    use HasFactory;
    protected $table = 'flights';
    protected $fillable = ['departure_date', 'flight_number', 'departure_airport', 'arrival_airport', 'distance', 'airline', 'user_id'];
}
