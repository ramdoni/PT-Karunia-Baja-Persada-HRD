<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;
    public $message;

    protected $rules = [
        'email' => 'required',
        'password' => 'required',
    ];

    public function render()
    {
        return view('livewire.login')
                ->layout('layouts.auth');
    }

    public function login()
    {
        $this->validate();
        
        $credentials = ['email'=>$this->email,'password'=>$this->password];

        if (Auth::attempt($credentials)) {
            \LogActivity::add('Login');
            // Authentication passed...
            return redirect('/');
        }
        else $this->message = __('Email / Password incorrect please try again');
    }
}
