<?php

namespace App\Jobs;

use App\Events\FlightDeleted;
use App\Models\Flight;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDeletedFlight implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $flightId;
    public int $userId;
    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, int $flightId)
    {
        $this->userId = $userId;
        $this->$flightId = $flightId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        FlightDeleted::dispatch($this->userId, $this->$flightId);
    }
}
