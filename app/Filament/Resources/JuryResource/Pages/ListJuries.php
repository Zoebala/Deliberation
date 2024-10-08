<?php

namespace App\Filament\Resources\JuryResource\Pages;

use App\Models\Jury;
use App\Models\Annee;
use Filament\Actions;
use App\Models\Section;
use Filament\Forms\Set;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Filament\Resources\JuryResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;

class ListJuries extends ListRecords
{
    protected static string $resource = JuryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),
            Actions\CreateAction::make()
            ->label("Ajouter un Jury")
            ->icon("heroicon-o-building-library")
            ->hidden(fn():bool => session("Annee_id") == null && session("section") == null),
            Action::make("annee_choix")
            ->label("Choix Année & Section")
            ->icon("heroicon-o-calendar-days")
            ->slideOver()
            ->modalSubmitActionLabel("Définir")
            ->form([
                Select::make("annee")
                ->label("Choix de l'année")
                ->searchable()
                ->required()
                ->live()
                ->afterStateUpdated(function($state,Set $set){
                    if($state){
                        $Annee=Annee::whereId($state)->get(["lib"]);
                        $set("lib_annee",$Annee[0]->lib);
                    }
                })
                ->options(Annee::query()->pluck("lib","id")),
                Hidden::make("lib_annee")
                ->label("Année Choisie")
                ->disabled()
                ->dehydrated(true),
                Select::make("section_id")
                ->label("Section")
                ->searchable()
                ->required()
                ->live()
                ->options(Section::query()->pluck("lib","id"))
                ->afterStateUpdated(function($state,Set $set){
                    if($state){
                        $Section=Section::whereId($state)->get(["lib"]);
                        $set("section",$Section[0]->lib);
                    }

                }),
                Hidden::make("section")
                ->label("Année Choisie")
                ->disabled()
                ->dehydrated(true),


            ])
            ->modalWidth(MaxWidth::Medium)
            ->modalIcon("heroicon-o-calendar")
            ->action(function(array $data){
                if(session('Annee_id')==NULL && session('Annee')==NULL){

                    session()->push("Annee_id", $data["annee"]);
                    session()->push("Annee", $data["lib_annee"]);
                    session()->push("section_id", $data["section_id"]);
                    session()->push("section", $data["section"]);

                }else{
                    session()->pull("Annee_id");
                    session()->pull("Annee");
                    session()->pull("section_id");
                    session()->pull("section");
                    session()->push("Annee_id", $data["annee"]);
                    session()->push("Annee", $data["lib_annee"]);
                    session()->push("section_id", $data["section_id"]);
                    session()->push("section", $data["section"]);

                }

                // dd(session('Annee'));
                Notification::make()
                ->title("Fixation de l'annee de travail en ".$data['lib_annee'])
                ->success()
                 ->duration(5000)
                ->send();
                 return redirect()->route("filament.admin.resources.juries.index");

            }),
        ];
    }

    public $defaultAction="Annee";

    public function Annee():Action
    {

        return Action::make("Annee")
                ->modalHeading("Choix Année & Section")
                ->modalSubmitActionLabel("Définir")
                ->slideOver()
                ->visible(fn():bool => session("section_id") == null)
                ->form([
                    Select::make("annee")
                    ->label("Choix de l'année")
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Annee=Annee::whereId($state)->get(["lib"]);
                            $set("lib_annee",$Annee[0]->lib);
                        }
                    })
                    ->options(Annee::query()->pluck("lib","id")),
                    Hidden::make("lib_annee")
                    ->label("Année Choisie")
                    ->disabled()
                    ->dehydrated(true),
                    Select::make("section_id")
                    ->label("Section")
                    ->searchable()
                    ->required()
                    ->live()
                    ->options(Section::query()->pluck("lib","id"))
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Section=Section::whereId($state)->get(["lib"]);
                            $set("section",$Section[0]->lib);
                        }

                    }),
                    Hidden::make("section")
                    ->label("Année Choisie")
                    ->disabled()
                    ->dehydrated(true),


                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-calendar")
                ->action(function(array $data){
                    if(session('Annee_id')==NULL){

                        session()->push("Annee_id", $data["annee"]);
                        session()->push("Annee", $data["lib_annee"]);
                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);

                    }else{
                        session()->pull("Annee_id");
                        session()->pull("Annee");
                        session()->pull("section_id");
                        session()->pull("section");
                        session()->push("Annee_id", $data["annee"]);
                        session()->push("Annee", $data["lib_annee"]);
                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);

                    }

                    Notification::make()
                    ->title("Annee ".$data['lib_annee']." & Section choisie : ".$data["section"])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.juries.index");

                });

    }

     public function getTabs():array
    {

        $Annee=Annee::where("id",session("Annee_id")[0] ?? 1)->first();
        $Section=Section::where("id",session("section_id")[0] ?? 1)->first();


            return [
                "$Annee->lib | $Section->lib"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("annee_id",session("Annee_id")[0] ?? 1)
                     ->where("section_id",session("section_id")[0] ?? 1);

                })->badge(Jury::query()
                ->where("annee_id",session("Annee_id")[0] ?? 1)
                ->where("section_id",session("section_id")[0] ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),


            ];

    }
}
