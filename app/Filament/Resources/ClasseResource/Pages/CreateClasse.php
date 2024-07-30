<?php

namespace App\Filament\Resources\ClasseResource\Pages;

use App\Filament\Resources\ClasseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClasse extends CreateRecord
{
    protected static string $resource = ClasseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ? string
    {
        return "Classe ajoutée avec succès!";
    }

    protected function mutateFormDataBeforeCreate(array $data):array
    {
        $data["jury_id"]=(int)session("jury_id")[0];
        return $data;
    }
}
