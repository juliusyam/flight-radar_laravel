<?php

namespace App\Providers;

use App\Listeners\BroadcastNewFlight;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Reverb\Events\MessageReceived;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(BroadcastNewFlight::class, MessageReceived::class);
    }
}

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Authentication related operations"
 * )
 * @OA\Tag(
 *     name="Flights",
 *     description="Flights related operations"
 * )
 * @OA\Tag(
 *     name="Notes",
 *     description="Notes related operations"
 * )
 * @OA\Info(
 *     version="1.0",
 *     title="Flight Radar - Laravel",
 *     description="This is a Rest API built with Laravel for tracking personal flight hidstory",
 *     @OA\Contact(name="Swagger API Team")
 * )
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="API server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="token",
 *     in="header",
 *     name="Authorization",
 *     type="apiKey"
 * ),
 */
class OpenApiSpec
{
}
