<?php

namespace App\Livewire;

use App\Models\Flights;
use Livewire\Component;

class HelloWorld extends Component
{
    public $name;
    public $count = 0;

    public $flights;

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
