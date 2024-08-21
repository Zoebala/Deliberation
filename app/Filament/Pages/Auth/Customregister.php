<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Pages\Auth\Register;
use Illuminate\Database\Eloquent\Model;

class Customregister extends Register
{
   


    protected function handleRegistration(array $data): Model
    {
        $user=$this->getUserModel()::create($data);
        $user->assignRole('Etudiant');

        return $user;
    }
    // public function form(Form $form):form
    // {
    //     return  $this->makeForm()
    //                  ->schema([
    //                     $this->getNameFormComponent(),
    //                     $this->getEmailFormComponent(),
    //                     $this->getPasswordFormComponent(),
    //                     $this->getPasswordConfirmationFormComponent(),
    //                  ])->statePath('data');
    // }

}
