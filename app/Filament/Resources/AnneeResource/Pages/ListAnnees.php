<?php

namespace App\Filament\Resources\AnneeResource\Pages;

use App\Filament\Resources\AnneeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnnees extends ListRecords
{
    protected static string $resource = AnneeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Ajouter une Année")
            ->icon("heroicon-o-calendar-days"),
        ];
    }
}
