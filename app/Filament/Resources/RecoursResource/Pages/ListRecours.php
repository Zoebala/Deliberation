<?php

namespace App\Filament\Resources\RecoursResource\Pages;

use App\Models\Jury;
use App\Models\Cours;
use Filament\Actions;
use App\Models\Classe;
use App\Models\Recours;
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
use App\Filament\Resources\RecoursResource;
use Filament\Resources\Pages\ListRecords\Tab;

class ListRecours extends ListRecords
{
    protected static string $resource = RecoursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Enregistrer un recours")
            ->icon("heroicon-o-document-text")
            ->hidden(fn():bool => session("classe_id") == null),
            Action::make("jury_choix")
                ->icon("heroicon-o-building-library")
                ->label("Choix de la Classe")
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
                            $Jury=Jury::whereId($state)->get(["lib"]);
                            $set("jury",$Jury[0]->lib);
                        }
                    }),
                    Hidden::make("jury")
                    ->disabled()
                    ->dehydrated(true),
                    Select::make("classe_id")
                    ->label("Classe")
                    ->options(function(Get $get){
                       return Classe::where("jury_id",$get("jury_id"))->pluck("lib","id");
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        $Classe=Classe::whereId($state)->get(["lib"]);
                        $set("classe",$Classe[0]->lib);

                    }),
                    Hidden::make("classe")
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
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }else{
                        session()->pull("section_id");
                        session()->pull("section");
                        session()->pull("jury_id", $data["jury_id"]);
                        session()->pull("jury", $data["jury"]);
                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);
                        session()->push("jury_id", $data["jury_id"]);
                        session()->push("jury", $data["jury"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }
                    Notification::make()
                    ->title("Jury Choisi :  ".$data['jury']." | ". $data["section"])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.recours.index");

                }),
        ];
    }

    public $defaultAction="jury";

    public function jury():Action
    {

        return Action::make("Section")
                ->modalHeading("Choix de la classe")
                ->modalSubmitActionLabel("Définir")
                ->visible(fn():bool => session("classe_id") == null)
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
                            $Jury=Jury::whereId($state)->get(["lib"]);
                            $set("jury",$Jury[0]->lib);
                        }
                    }),
                    Hidden::make("jury")
                    ->disabled()
                    ->dehydrated(true),
                    Select::make("classe_id")
                    ->label("Classe")
                    ->options(function(Get $get){
                       return Classe::where("jury_id",$get("jury_id"))->pluck("lib","id");
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        $Classe=Classe::whereId($state)->get(["lib"]);
                        $set("classe",$Classe[0]->lib);

                    }),
                    Hidden::make("classe")
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
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }else{
                        session()->pull("section_id");
                        session()->pull("section");
                        session()->pull("jury_id", $data["jury_id"]);
                        session()->pull("jury", $data["jury"]);
                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);
                        session()->push("jury_id", $data["jury_id"]);
                        session()->push("jury", $data["jury"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }
                    Notification::make()
                    ->title("Jury Choisi :  ".$data['jury']." | ". $data["section"])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.recours.index");

                });

    }

    public function getTabs():array
    {

        $Section=Section::where("id",session("section_id")[0] ?? 1)->first();

        $Jury=Jury::whereId(session("jury_id")[0] ?? 1)->first();

        $Classe=Classe::where("id",session("classe_id")[0] ?? 1)->first();

            return [
                "$Section->lib | $Jury->lib | $Classe->lib"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("classe_id",session("classe_id")[0] ?? 1);

                })->badge("Total recours : ".Recours::where("classe_id",session("classe_id")[0] ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),
                'Tous'=>Tab::make()
                ->badge(Recours::query()->count()),

            ];

    }
}
