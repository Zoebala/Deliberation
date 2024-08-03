<?php

namespace App\Filament\Resources\SemestreResource\Pages;

use App\Models\Annee;
use Filament\Actions;
use Filament\Forms\Set;
use App\Models\Semestre;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SemestreResource;
use Filament\Resources\Pages\ListRecords\Tab;

class ListSemestres extends ListRecords
{
    protected static string $resource = SemestreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Ajouter un Semestre")
            ->icon("heroicon-o-calendar")
            ->hidden(fn():bool => session("Annee_id") == null),
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


                return redirect()->route("filament.admin.resources.semestres.index");


            }),
        ];
    }

    public $defaultAction="Annee";

    public function Annee():Action
    {

        return Action::make("Annee")
                ->modalHeading("Définition de l'année de travail")
                ->modalSubmitActionLabel("Définir")
                ->visible(fn():bool => session("Annee_id") == null)
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
                     return redirect()->route("filament.admin.resources.semestres.index");

                });

    }

     public function getTabs():array
    {

        $Annee=Annee::where("id",session("Annee_id") ?? 1)->first();


            return [
                "$Annee->lib"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("annee_id",session("Annee_id") ?? 1);

                })->badge(Semestre::query()
                ->where("annee_id",session("Annee_id") ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),
                'Tous'=>Tab::make()
                ->badge(Semestre::query()->count()),

            ];

    }


}
