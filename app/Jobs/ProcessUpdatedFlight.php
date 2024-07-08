<?php

namespace App\Jobs;

use App\Events\FlightUpdated;
use App\Models\Flight;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUpdatedFlight implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Flight $flight;
    /**
     * Create a new job instance.
     */
    public function __construct(Flight $flight)
    {
      $this->flight = $flight;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      FlightUpdated::dispatch($this->flight);
    }
}
