import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

Echo.channel(`flights`)
    .listen('NewFlightAdded', (event) => {
        console.log(event.flights.flight_number);
    })
    .listen('FlightUpdated', (event) => {
      console.log('Flight updated:', event.flight);
    })
    .listen('FlightDeleted', (event) => {
        console.log('Flight deleted:', event.flightData);
    });
