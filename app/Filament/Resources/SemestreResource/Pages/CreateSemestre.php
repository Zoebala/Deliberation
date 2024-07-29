<?php

namespace App\Filament\Resources\SemestreResource\Pages;

use App\Filament\Resources\SemestreResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSemestre extends CreateRecord
{
    protected static string $resource = SemestreResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ? string
    {
        return "Semestre ajouté avec succès!";
    }

    protected function mutateformDataBeforeCreate(array $data):array
    {
        $data["annee_id"]=(int)session("Annee_id")[0];
        return $data;
    }
}
