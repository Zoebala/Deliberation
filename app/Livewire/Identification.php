<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Hash;

class Identification extends Component
{
    #[Rule("required|max:15|min:3")]
    public $name;
    #[Rule("required|email|unique:users")]
    public $email;
    #[Rule("required|min:8")]
    public $password;
    public function save(){
        $this->validate();

        User::create([
            "name" => $this->name,
            "email" => strtolower($this->email),
            "password" => Hash::make($this->password)
        ]);
        $User=User::whereEmail($this->email)->first();
        //attribution des permissions à l'utilisateur
        $User->assignRole("Etudiant");

        return redirect()->route("filament.admin.auth.login");
    }

    public function render()
    {
        return view('livewire.identification');
    }
}
