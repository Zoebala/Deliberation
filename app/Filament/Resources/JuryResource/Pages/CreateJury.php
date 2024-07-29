<?php

namespace App\Filament\Resources\JuryResource\Pages;

use App\Filament\Resources\JuryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJury extends CreateRecord
{
    protected static string $resource = JuryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ? string
    {
        return "Jury ajouté avec succès!";
    }

    protected function mutateFormDataBeforeCreate(array $data):array
    {
        $data["annee_id"]=(int)session("Annee_id")[0];
        return $data;
    }
}
