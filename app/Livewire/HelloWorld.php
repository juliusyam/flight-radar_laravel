<?php

namespace App\Livewire;

use App\Models\Flights;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class HelloWorld extends Component
{
    public $email = '';
    public $password = '';

    public $token = '';

    public $count = 0;

    public $flights;

    public function login() {
        $token = Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (!$token) {
            Log::debug('Unable to authenticate');
            return;
        }

        $this->token = $token;
    }

    public function increment() {
        $this->count++;
    }

    public function decrement() {
        $this->count--;
    }

    public function render()
    {
        $this->flights = Flights::all();
        return view('livewire.hello-world');
    }
}
