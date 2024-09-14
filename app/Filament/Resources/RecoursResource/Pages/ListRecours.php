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
use App\Models\Semestre;

class ListRecours extends ListRecords
{
    protected static string $resource = RecoursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),
            Actions\CreateAction::make()
            ->label("Enregistrer un recours")
            ->icon("heroicon-o-document-text")
            ->hidden(fn():bool => session("classe_id") == null),
            Action::make("classe_choix")
                 ->icon("heroicon-o-building-office")
                //  ->hidden(fn():bool => Auth()->user()->hasRole("Etudiant"))
                 ->label("Choix Classe & Semestre")
                 ->slideOver()
                 ->modalSubmitActionLabel("Définir")
                ->form([
                    Select::make("section_id")
                    ->label("Section")
                    ->options(Section::all()->pluck("lib","id"))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function(Set $set){
                        $set("jury_id",null);
                        $set("classe_id",null);
                    }),
                    Select::make("jury_id")
                    ->label("Jury")
                    ->options(function(Get $get){
                        return Jury::where("section_id",$get("section_id"))->pluck("lib","id");
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function(Set $set){
                        $set("classe_id",null);
                    }),
                    Select::make("classe_id")
                    ->label("Classe")
                    ->options(function(Get $get){
                        return Classe::where("jury_id", $get("jury_id"))->pluck("lib","id");

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
                    Select::make("semestre_id")
                    ->label("Semestre")
                    ->options(Semestre::all()->pluck("lib","id"))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Semestre=Semestre::find($state);
                            $set("semestre",$Semestre->lib);
                        }else{
                            $set("jury_id",null);
                            $set("classe_id",null);
                        }
                    }),
                    Hidden::make("semestre")
                    ->disabled()
                    ->dehydrated(true),
                ])
                ->slideOver()
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('classe_id')==NULL && session('classe')==NULL){

                        session()->push("section_id", $data["section_id"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);
                        session()->push("semestre_id", $data["semestre_id"]);
                        session()->push("semestre", $data["semestre"]);

                    }else{

                        session()->pull("section_id");
                        session()->pull("classe_id");
                        session()->pull("classe");
                        session()->pull("semestre_id");
                        session()->pull("semestre");
                        session()->push("section_id", $data["section_id"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);
                        session()->push("semestre_id", $data["semestre_id"]);
                        session()->push("semestre", $data["semestre"]);


                    }
                    Notification::make()
                    ->title("Classe Choisie :  ".$data['classe']." | Semestre : ".$data['semestre'])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.recours.index");

                }),
                Action::make("liaison_choix")
                ->label("Liaison Utilisateur-Etudiant")
                ->modalSubmitActionLabel("Définir")
                ->visible(fn():bool =>  Auth()->user()->hasRole("Etudiant"))
                ->hidden(fn():bool => Etudiant::where("user_id",Auth()->user()->id)->exists())
                ->form([
                    Select::make("section_id")
                    ->label("Section")
                    ->options(Section::all()->pluck("lib","id"))
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function(Set $set){
                        $set("jury_id",null);
                        $set("etudiant_id",null);
                    })
                    ->live(),
                    Select::make("jury_id")
                    ->label("Jury")
                    ->options(function(Get $get){
                        if(filled($get("section_id"))){
                            return Jury::where("section_id",$get("section_id"))->pluck("lib","id");
                        }
                    })
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
                        if(filled($get("jury_id"))){
                             return Classe::where("jury_id",$get("jury_id"))->pluck("lib","id");
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
                ->slideOver()
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
                    Select::make("jury_id")
                    ->label("Jury")
                    ->options(function(Get $get){
                        if(filled($get("section_id"))){
                            return Jury::where("section_id",$get("section_id"))->pluck("lib","id");
                        }
                    })
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
                        if(filled($get("jury_id"))){
                             return Classe::where("jury_id",$get("jury_id"))->pluck("lib","id");
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
        $libJury= $Jury ? $Jury->lib :"veuillez choisir le jury";


        $Classe=Classe::where("id",session("classe_id")[0] ?? 1)->first();

        //Récupération de la session
        $Semestre=Semestre::where("id",session("semestre_id")[0] ?? 1)->first();

        if(session("semestre_id") != null && session("classe_id") != null && session("section_id") != null){

            $label="$Section->lib | $libJury | $Classe->lib | Semestre: $Semestre->lib";
        }else{
            $label="";
        }

            return [
                "$label"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                    if(Auth()->user()->hasRole("Etudiant") && session("etudiant_id")==null){
                        $query->where("classe_id",null)
                               ->where("semestre_id",null);
                    }elseif(session("semestre_id")==null){
                        $query->where("classe_id",null)
                               ->where("semestre_id",null);
                    }
                    else{
                        $query->where("classe_id",session("classe_id")[0] ?? 1)
                              ->where("semestre_id",session("semestre_id")[0] ?? 1);
                    }

                })->badge("Total recours : ".Recours::where("classe_id",session("classe_id")[0] ?? 1)
                                                     ->where("semestre_id",session("semestre_id")[0] ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),


            ];

    }
}
