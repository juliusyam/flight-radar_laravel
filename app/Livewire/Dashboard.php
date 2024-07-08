<?php

namespace App\Livewire;

use App\Models\Flights;
use App\Providers\FlightProvider;
use Livewire\Attributes\Session;
use Livewire\Component;

class Dashboard extends Component
{
    #[Session(key: 'user')]
    public $user;

    public function render()
    {
        if (!$this->user) {
            $this->redirect('/');
        }

        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}
