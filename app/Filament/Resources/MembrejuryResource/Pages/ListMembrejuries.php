<?php

namespace App\Filament\Resources\MembrejuryResource\Pages;

use App\Models\Jury;
use Filament\Actions;
use App\Models\Section;
use Filament\Forms\Get;
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
            Actions\Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),
            Actions\CreateAction::make()
            ->label("Ajouter Membre Jury")
            ->icon("heroicon-o-user-plus")
            ->hidden(fn():bool => session("section_id") == null),
            Action::make("jury_choice")
            ->slideOver()
            ->icon("heroicon-o-building-library")
            ->label("Choix du jury")
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
                 return redirect()->route("filament.admin.resources.membrejuries.index");

            }),
        ];
    }

    public $defaultAction="jury";

    public function jury():Action
    {

        return Action::make("Section")
                ->slideOver()
                ->modalHeading("Choix du jury")
                ->modalSubmitActionLabel("Définir")
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
                     return redirect()->route("filament.admin.resources.membrejuries.index");

                });

    }


    public function getTabs():array
    {

        $Section=Section::where("id",session("section_id")[0] ?? 1)->first();

        $Jury=Jury::whereId(session("jury_id")[0] ?? 1)->first();
        $libjurie=$Jury->lib ?? "choisir un jury";

            return [
                "$Section->lib |  $libjurie"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->join("juries","juries.id","membrejuries.jury_id")
                        ->where("section_id",session("section_id")[0] ?? 1)
                        ->where("jury_id",session("jury_id")[0] ?? 1);

                })->badge(Membrejury::query()
                ->join("juries","juries.id","membrejuries.jury_id")
                ->where("section_id",session("section_id")[0] ?? 1)
                ->where("jury_id",session("jury_id")[0] ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),


            ];

    }
}
