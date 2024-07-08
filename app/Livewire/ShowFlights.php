<?php

namespace App\Livewire;

use Livewire\Attributes\Session;
use Livewire\Component;

class ShowFlights extends Component
{
    #[Session(key: 'user')]
    public $user;

    public function render()
    {
        if (!$this->user) {
            $this->redirect('/');
        }

        return view('livewire.show-flights')
            ->layout('layouts.app');
    }
}
