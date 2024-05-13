<?php

namespace App\Jobs;

use App\Events\FlightDeleted;
use App\Models\Flights;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDeletedFlight implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $flight;
    /**
     * Create a new job instance.
     */
    public function __construct(array $flight)
    {
      $this->flight = $flight;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

      FlightDeleted::dispatch($this->flight);
    }
}
