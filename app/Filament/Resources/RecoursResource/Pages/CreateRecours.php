<?php

namespace App\Filament\Resources\RecoursResource\Pages;

use App\Models\Jury;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\RecoursResource;

class CreateRecours extends CreateRecord
{
    protected static string $resource = RecoursResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ? string
    {
        return "Recours enregistré avec succès!";
    }

    protected function mutateFormDataBeforeCreate(array $data):array
    {
        //Recherche de l'id Année
        $Jury=Jury::where("id",session("jury_id")[0] ?? 1)
                   ->first();


        $data["annee_id"]=$Jury->annee_id;

        return $data;
    }
}
