<?php

namespace App\Filament\Resources\MembrejuryResource\Pages;

use App\Filament\Resources\MembrejuryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMembrejury extends CreateRecord
{
    protected static string $resource = MembrejuryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ? string
    {
        return "Membre Jury ajouté avec succès!";
    }
}
