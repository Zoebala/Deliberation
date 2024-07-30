<?php

namespace App\Filament\Resources\AnneeResource\Pages;

use App\Models\Annee;
use Filament\Actions;
use Filament\Forms\Set;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Filament\Resources\AnneeResource;
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
            Action::make("annee")
            ->label("Choix année de travail")
            ->form([
                Select::make("annee")
                ->label("Choix de l'année")
                ->searchable()
                ->required()
                ->live()
                ->afterStateUpdated(function($state,Set $set){
                    $Annee=Annee::whereId($state)->get(["lib"]);
                    $set("lib_annee",$Annee[0]->lib);



                })
                ->options(Annee::query()->pluck("lib","id")),
                Hidden::make("lib_annee")
                ->label("Année Choisie")
                ->disabled()
                // ->hidden()
                ->dehydrated(true)
            ])
            ->modalWidth(MaxWidth::Medium)
            ->modalIcon("heroicon-o-calendar")
            ->action(function(array $data){
                if(session('Annee_id')==NULL && session('Annee')==NULL){
                    session()->push("Annee_id", $data["annee"]);
                    session()->push("Annee", $data["lib_annee"]);

                }else{
                    session()->pull("Annee_id");
                    session()->pull("Annee");
                    session()->push("Annee_id", $data["annee"]);
                    session()->push("Annee", $data["lib_annee"]);
                }

                // dd(session('Annee'));
                Notification::make()
                ->title("Fixation de l'annee de travail en ".$data['lib_annee'])
                ->success()
                 ->duration(5000)
                ->send();


                return redirect("/admin");


            }),
        ];
    }
}
