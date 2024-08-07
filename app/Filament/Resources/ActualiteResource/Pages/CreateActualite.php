<?php

namespace App\Filament\Resources\ActualiteResource\Pages;

use App\Filament\Resources\ActualiteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateActualite extends CreateRecord
{
    protected static string $resource = ActualiteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ? string
    {
        return "Actualité ajoutée avec succès!";
    }
}
