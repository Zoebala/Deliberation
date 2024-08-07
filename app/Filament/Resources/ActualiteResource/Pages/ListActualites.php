<?php

namespace App\Filament\Resources\ActualiteResource\Pages;

use App\Filament\Resources\ActualiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActualites extends ListRecords
{
    protected static string $resource = ActualiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),
            Actions\CreateAction::make()
            ->label("Ajouter une actualité")
            ->icon("heroicon-o-document-text"),
        ];
    }
}
