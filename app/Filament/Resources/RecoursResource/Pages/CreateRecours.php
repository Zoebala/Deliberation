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

        $data["classe_id"]=(int)session("classe_id")[0] ?? 1;
        $data["semestre_id"]=(int)session("semestre_id")[0] ?? 1;

        return $data;
    }
}
