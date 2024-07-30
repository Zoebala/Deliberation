<?php

namespace App\Filament\Resources\EtudiantResource\Pages;

use App\Filament\Resources\EtudiantResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEtudiant extends CreateRecord
{
    protected static string $resource = EtudiantResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ? string
    {
        return "Etudiant ajouté avec succès!";
    }

    protected function mutateFormDataBeforeCreate(array $data):array
    {
        $data["classe_id"]=(int)session("classe_id")[0];
        
        return $data;
    }
}
