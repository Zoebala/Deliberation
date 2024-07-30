<?php

namespace App\Filament\Resources\CoursResource\Pages;

use App\Filament\Resources\CoursResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCours extends CreateRecord
{
    protected static string $resource = CoursResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ? string
    {
        return "Cours ajouté avec succès!";
    }

    protected function mutateFormDataBeforeCreate(array $data):array
    {
        $data["semestre_id"]=(int)session("semestre_id")[0];
        $data["classe_id"]=(int)session("classe_id")[0];
        return $data;
    }
}
