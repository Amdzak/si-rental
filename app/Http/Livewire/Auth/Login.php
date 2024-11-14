<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember_me = false;

    protected $rules = [
        'email' => 'required',
        'password' => 'required',
    ];

    public function mount() {
        if(auth()->user()){
            redirect('/dashboard');
        }
        $this->fill(['email' => '', 'password' => '']);
    }

    public function login() {
        $credentials = $this->validate();
        if(auth()->attempt(['username' => $this->email, 'password' => $this->password], $this->remember_me)) {
            $user = User::where(["username" => $this->email])->first();
            auth()->login($user, $this->remember_me);
            return redirect()->intended('/dashboard');        
        }
        else{
            return $this->addError('username', trans('auth.failed')); 
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
