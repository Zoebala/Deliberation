<?php

namespace App\Filament\Resources\SectionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\SectionResource;
use App\Filament\Resources\SectionResouceResource\Widgets\StatSectionOverview;
use App\Filament\Resources\SectionResource\Widgets\SectionNombreRecoursparJury;
use App\Filament\Resources\SectionResource\Widgets\SectionEffectifClasseparJury;
use App\Filament\Resources\SectionResource\Widgets\SectionNombreRecoursparClasse;

class ListSections extends ListRecords
{
    protected static string $resource = SectionResource::class;
    protected static bool $isLazy = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),
            Actions\CreateAction::make()
            ->label("Nouvelle Section/faculté")
            ->icon("heroicon-o-building-office-2"),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            StatSectionOverview::class,
            SectionEffectifClasseparJury::class,
            SectionNombreRecoursparJury::class,
            SectionNombreRecoursparClasse::class,
        ];
    }

    protected function getWidgets():array
    {
        return [
            StatSectionOverview::class,
            SectionEffectifClasseparJury::class,
            SectionNombreRecoursparJury::class,
            SectionNombreRecoursparClasse::class,
        ];
    }
}
