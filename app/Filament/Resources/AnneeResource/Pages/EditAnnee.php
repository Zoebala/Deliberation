<?php

namespace App\Filament\Resources\AnneeResource\Pages;

use App\Filament\Resources\AnneeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnnee extends EditRecord
{
    protected static string $resource = AnneeResource::class;

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
