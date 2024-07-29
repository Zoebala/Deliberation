<?php

namespace App\Filament\Resources\AnneeResource\Pages;

use App\Filament\Resources\AnneeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnee extends CreateRecord
{
    protected static string $resource = AnneeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ? string
    {
        return "Année ajoutée avec succès!";
    }
}
