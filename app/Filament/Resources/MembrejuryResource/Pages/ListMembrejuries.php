<?php

namespace App\Filament\Resources\MembrejuryResource\Pages;

use App\Models\Jury;
use Filament\Actions;
use App\Models\Section;
use Filament\Forms\Set;
use App\Models\Membrejury;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\MembrejuryResource;

class ListMembrejuries extends ListRecords
{
    protected static string $resource = MembrejuryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Ajouter Membre Jury")
            ->icon("heroicon-o-user-plus")
            ->hidden(fn():bool => session("section_id") == null),
            Action::make("Choix Section")
                ->icon("heroicon-o-building-office-2")
                ->modalHeading("Choix de la Section")
                ->modalSubmitActionLabel("Définir")
                ->form([
                    Select::make("section_id")
                    ->label("Section")
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Section=Section::whereId($state)->get(["lib"]);
                            $set("section",$Section[0]->lib);
                        }

                    })
                ->options(Section::query()->pluck("lib","id")),
                    Hidden::make("section")
                    ->label("Année Choisie")
                    ->disabled()
                    // ->hidden()
                    ->dehydrated(true)


                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('section_id')==NULL && session('section')==NULL){

                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);

                    }else{
                        session()->pull("section_id");
                        session()->pull("section");
                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);

                    }

                    // dd(session('Annee'));
                    Notification::make()
                    ->title("Section Choisie :  ".$data['section'])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.membrejuries.index");

                })
        ];
    }

    public $defaultAction="Section";

    public function Section():Action
    {

        return Action::make("Section")
                ->modalHeading("Choix de la Section")
                ->modalSubmitActionLabel("Définir")
                ->visible(fn():bool => session("section_id") == null)
                ->form([
                    Select::make("section_id")
                    ->label("Section")
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Section=Section::whereId($state)->get(["lib"]);
                            $set("section",$Section[0]->lib);
                        }

                    })
                    ->options(Section::query()->pluck("lib","id")),
                    Hidden::make("section")
                    ->label("Année Choisie")
                    ->disabled()
                    ->dehydrated(true)


                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('section_id')==NULL && session('section')==NULL){

                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);

                    }else{
                        session()->pull("section_id");
                        session()->pull("section");
                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);

                    }

                    // dd(session('Annee'));
                    Notification::make()
                    ->title("Section Choisie :  ".$data['section'])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.membrejuries.index");

                });

    }


    public function getTabs():array
    {

        $Section=Jury::join("sections","sections.id","juries.section_id")
                ->where("section_id",session("section_id")[0] ?? 1)->first(["sections.lib as section"]);


            return [
                "Section $Section->section"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->join("juries","juries.id","membrejuries.jury_id")->where("section_id",session("section_id")[0] ?? 1);

                })->badge(Membrejury::query()
                ->join("juries","juries.id","membrejuries.jury_id")
                ->where("section_id",session("section_id")[0] ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),
                'Tous'=>Tab::make()
                ->badge(Membrejury::query()->count()),

            ];

    }
}
