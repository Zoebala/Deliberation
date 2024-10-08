<?php

namespace App\Filament\Resources\ClasseResource\Pages;

use App\Models\Jury;
use Filament\Actions;
use App\Models\Classe;
use App\Models\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClasseResource;
use Filament\Resources\Pages\ListRecords\Tab;

class ListClasses extends ListRecords
{
    protected static string $resource = ClasseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),
            Actions\CreateAction::make()
            ->label("Ajouter une Classe")
            ->icon("heroicon-o-plus-circle")
            ->hidden(fn():bool => session("jury_id") == null),
             Action::make("Choix Jury")
                ->modalHeading("Choix du jury")
                ->icon("heroicon-o-building-library")
                ->slideOver()
                ->modalSubmitActionLabel("Définir")
                ->form([
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
                    Select::make("jury_id")
                    ->label("Jury")
                    ->options(function(Get $get){
                        return Jury::where("section_id",$get("section_id"))->pluck("lib","id");
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Jury=Jury::where("id",$state)->get(["lib"]);
                            $set("jury",$Jury[0]->lib);
                        }

                    }),
                    Hidden::make("jury")
                    ->label("Année Choisie")
                    ->disabled()
                    // ->hidden()
                    ->dehydrated(true),


                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('section_id')==NULL && session('section')==NULL){

                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);
                        session()->push("jury_id", $data["jury_id"]);
                        session()->push("jury", $data["jury"]);

                    }else{
                        session()->pull("section_id");
                        session()->pull("section");
                        session()->pull("jury_id", $data["jury_id"]);
                        session()->pull("jury", $data["jury"]);
                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);
                        session()->push("jury_id", $data["jury_id"]);
                        session()->push("jury", $data["jury"]);

                    }

                    // dd(session('Annee'));
                    Notification::make()
                    ->title("Jury Choisi :  ".$data['jury']." | ". $data["section"])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.classes.index");

                }),
        ];
    }

    public $defaultAction="jury";

    public function jury():Action
    {

        return Action::make("Section")
                ->modalHeading("Choix du jury")
                ->modalSubmitActionLabel("Définir")
                ->slideOver()
                ->visible(fn():bool => session("jury_id") == null)
                ->form([
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
                    // ->hidden()
                    ->dehydrated(true),
                    Select::make("jury_id")
                    ->label("Jury")
                    ->options(function(Get $get){

                        return Jury::where("section_id",$get("section_id"))->pluck("lib","id");
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Jury=Jury::whereId($state)->get(["lib"]);
                            $set("jury",$Jury[0]->lib);
                        }

                    }),
                    Hidden::make("jury")
                    ->label("Année Choisie")
                    ->disabled()
                    ->dehydrated(true),


                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('section_id')==NULL && session('section')==NULL){

                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);
                        session()->push("jury_id", $data["jury_id"]);
                        session()->push("jury", $data["jury"]);

                    }else{
                        session()->pull("section_id");
                        session()->pull("section");
                        session()->pull("jury_id", $data["jury_id"]);
                        session()->pull("jury", $data["jury"]);
                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);
                        session()->push("jury_id", $data["jury_id"]);
                        session()->push("jury", $data["jury"]);

                    }

                    // dd(session('Annee'));
                    Notification::make()
                    ->title("Jury Choisi :  ".$data['jury']." | ". $data["section"])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.classes.index");

                });

    }


    public function getTabs():array
    {

        $Section=Section::where("id",session("section_id")[0] ?? 1)->first();
        $Jury=Jury::where("id",session("jury_id")[0] ?? 1)->first();
        $libjurie=$Jury->lib ?? "choisir un jury";

            return [
                "$Section->lib | $libjurie"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("classes.jury_id",session("jury_id")[0] ?? 1);

                })->badge(Classe::join("juries","juries.id","classes.jury_id")
                                ->where("juries.id",session("jury_id")[0] ?? 1)
                                 ->count())
                ->icon("heroicon-o-calendar-days"),


            ];

    }
}
