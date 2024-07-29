<?php

namespace App\Filament\Resources\MembrejuryResource\Pages;

use App\Filament\Resources\MembrejuryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMembrejury extends EditRecord
{
    protected static string $resource = MembrejuryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
