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
use App\Models\Etudiant;
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
            Action::make("classe_choix")
                ->icon("heroicon-o-building-library")
                ->hidden(fn():bool => Auth()->user()->hasRole("Etudiant"))
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
                        if($state){
                            $Classe=Classe::whereId($state)->get(["lib"]);
                            $set("classe",$Classe[0]->lib);
                        }

                    }),
                    Hidden::make("classe")
                    ->disabled()
                    ->dehydrated(true),
                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('classe_id')==NULL && session('classe')==NULL){

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
                Action::make("liaison_choix")
                ->label("Liaison Utilisateur-Etudiant")
                ->modalSubmitActionLabel("Définir")
                ->visible(fn():bool =>  Auth()->user()->hasRole("Etudiant"))
                ->hidden(fn():bool => !session("etudiant_id")==null)
                ->form([
                    Select::make("section_id")
                    ->label("Section")
                    ->options(Section::all()->pluck("lib","id"))
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function(Set $set){
                        $set("classe_id",null);
                        $set("etudiant_id",null);
                    })
                    ->live(),
                    Select::make("classe_id")
                    ->label("Classe")
                    ->options(function(Get $get){
                        if(filled($get("section_id"))){
                            $Jury=Jury::where("section_id",$get("section_id"))->first();
                             return Classe::where("jury_id",$Jury->id)->pluck("lib","id");
                        }
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Classe=Classe::whereId($state)->get(["lib"]);
                            $set("classe",$Classe[0]->lib);
                        }
                        $set("etudiant_id",null);

                    }),
                    Hidden::make("classe")
                    ->disabled()
                    ->dehydrated(true),
                    Select::make('etudiant_id')
                            ->label("Etudiant")
                            ->options(function(Get $get){
                                return Etudiant::where("classe_id",$get("classe_id"))->pluck("nom","id");
                            })
                            ->preload()
                            ->searchable()
                            ->live()
                            ->required()
                            ->helperText(function($state){
                                if($state){
                                    $Etudiant=Etudiant::where("id",$state)->first();
                                    return $Etudiant->nom." ".$Etudiant->postnom." ".$Etudiant->prenom." | Genre : ".$Etudiant->genre;
                                }else{
                                    return "";
                                }
                            }),


                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('classe_id')==NULL && session('classe')==NULL){
                        //Mise à jour table Etudiant
                        Etudiant::where("id",$data["etudiant_id"])
                                ->update([
                                    "user_id"=>Auth()->user()->id,
                                ]);

                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);
                        session()->push("etudiant_id", $data["etudiant_id"]);
                    }else{
                        //Mise à jour table Etudiant
                        Etudiant::where("id",$data["etudiant_id"])
                        ->update([
                            "user_id"=>Auth()->user()->id,
                        ]);
                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
                        session()->pull("etudiant_id", $data["etudiant_id"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);
                        session()->push("etudiant_id", $data["etudiant_id"]);
                    }
                    Notification::make()
                    ->title("Classe Choisie :  ".$data['classe'])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.coupons.index");

                }),
        ];
    }

    public $defaultAction="liaison";
    public function liaison():Action
    {
        $Etudiant=Etudiant::where("user_id",Auth()->user()->id)->first();

        return Action::make("liaison")
                ->modalHeading("Liaison User-Etudiant")
                ->modalSubmitActionLabel("Définir")
                ->visible(fn():bool =>  $Etudiant == null)
                ->hidden(fn():bool =>  Auth()->user()->hasRole(["Admin","Jury"]))
                ->form([
                    Select::make("section_id")
                    ->label("Section")
                    ->options(Section::all()->pluck("lib","id"))
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function(Set $set){
                        $set("classe_id",null);
                        $set("etudiant_id",null);
                    })
                    ->live(),
                    Select::make("classe_id")
                    ->label("Classe")
                    ->options(function(Get $get){
                        if(filled($get("section_id"))){
                            $Jury=Jury::where("section_id",$get("section_id"))->first();
                             return Classe::where("jury_id",$Jury->id)->pluck("lib","id");
                        }
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Classe=Classe::whereId($state)->get(["lib"]);
                            $set("classe",$Classe[0]->lib);
                        }
                        $set("etudiant_id",null);

                    }),
                    Hidden::make("classe")
                    ->disabled()
                    ->dehydrated(true),
                    Select::make('etudiant_id')
                            ->label("Etudiant")
                            ->options(function(Get $get){
                                return Etudiant::where("classe_id",$get("classe_id"))->pluck("nom","id");
                            })
                            ->preload()
                            ->searchable()
                            ->live()
                            ->required()
                            ->helperText(function($state){
                                if($state){
                                    $Etudiant=Etudiant::where("id",$state)->first();
                                    return $Etudiant->nom." ".$Etudiant->postnom." ".$Etudiant->prenom." | Genre : ".$Etudiant->genre;
                                }else{
                                    return "";
                                }
                            }),


                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('classe_id')==NULL && session('classe')==NULL){
                        //Mise à jour table Etudiant
                        Etudiant::where("id",$data["etudiant_id"])
                                ->update([
                                    "user_id"=>Auth()->user()->id,
                                ]);

                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);
                        session()->push("etudiant_id", $data["etudiant_id"]);
                    }else{
                        //Mise à jour table Etudiant
                        Etudiant::where("id",$data["etudiant_id"])
                        ->update([
                            "user_id"=>Auth()->user()->id,
                        ]);
                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
                        session()->pull("etudiant_id", $data["etudiant_id"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);
                        session()->push("etudiant_id", $data["etudiant_id"]);
                    }
                    Notification::make()
                    ->title("Classe Choisie :  ".$data['classe'])
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
                    if(Auth()->user()->hasRole("Etudiant") && session("etudiant_id")==null){
                        $query->where("classe_id",null);
                    }else{
                        $query->where("classe_id",session("classe_id")[0] ?? 1);
                    }

                })->badge("Total recours : ".Recours::where("classe_id",session("classe_id")[0] ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),
               

            ];

    }
}
