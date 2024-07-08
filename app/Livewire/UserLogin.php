<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Session;
use Livewire\Component;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserLogin extends Component
{
    #[Session(key: 'user')]
    public $user;

    public string | null $token = null;

    public string $email = '';
    public string $password = '';

    public function login() {
        Log::debug("registered click");

        $token = Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (!$token) {
            Log::debug('Unable to authenticate');
            return;
        }

        $this->token = $token;

        $user = JWTAuth::user();

        $this->user = $user;

        $this->redirect('/dashboard');
    }

    public function logout() {
        $this->token = null;
        $this->user = null;

        $this->redirect('/');
    }

    public function render()
    {
        return view('livewire.user-login')
            ->layout('layouts.app');
    }
}
