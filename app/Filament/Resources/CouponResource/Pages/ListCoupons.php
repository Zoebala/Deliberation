<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Models\Jury;
use App\Models\Annee;
use Filament\Actions;
use App\Models\Classe;
use App\Models\Coupon;
use App\Models\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Etudiant;
use App\Models\Semestre;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CouponResource;
use Filament\Resources\Pages\ListRecords\Tab;

class ListCoupons extends ListRecords
{
    protected static string $resource = CouponResource::class;

    protected function getHeaderActions(): array
    {

        return [
            Actions\Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),
            Actions\CreateAction::make()
                    ->label("Enregistrer une fiche")
                    ->icon("heroicon-o-clipboard-document-list")
                    ->hidden(fn():bool => session("classe_id") == null),
                Action::make("classe_choix")
                 ->icon("heroicon-o-building-office")
                 ->hidden(fn():bool => Auth()->user()->hasRole("Etudiant"))
                 ->label("Choix Classe & Semestre")
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
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('classe_id')==NULL && session('classe')==NULL){

                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);
                        session()->push("semestre_id", $data["semestre_id"]);
                        session()->push("semestre", $data["semestre"]);

                    }else{

                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
                        session()->pull("semestre_id", $data["semestre_id"]);
                        session()->pull("semestre", $data["semestre"]);
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
                     return redirect()->route("filament.admin.resources.coupons.index");

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
                     return redirect()->route("filament.admin.resources.coupons.index");

                });

    }

    public function getTabs():array
    {

        $Classe=Classe::where("id",session("classe_id")[0] ?? 1)->first();

        //Recherche de l'id Année
        $Cl=Classe::join("juries","juries.id","classes.jury_id")
                   ->where("classes.id",session("classe_id")[0] ?? 1)
                   ->first();
        //Récupération de l'effectif pour une classe choisie
        $Effectif=Etudiant::where("classe_id",session("classe_id")[0] ?? 1)->count();
        //Récupération de l'année de l'enregistrement du coupon
        $Annee=Annee::where("id",$Cl->annee_id)->first();
        //Récupération de la session
        $Semestre=Semestre::where("id",session("semestre_id")[0] ?? 1)->first();


            return [
                "$Classe->lib | Semestre : $Semestre->lib | $Annee->lib | Effectif Etudiant : $Effectif"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                    if(Auth()->user()->hasRole("Etudiant") && session("etudiant_id")==null){
                        $query->where("classe_id",null);
                    }elseif(session("semestre_id")==null){
                        $query->where("classe_id",null)
                               ->where("semestre_id",null);
                    }
                    else{
                        $query->where("classe_id",session("classe_id")[0] ?? 1)
                             ->where("semestre_id",session("semestre_id")[0] ?? 1);
                    }

                })->badge("Fiche(s) remplie(s) : ".Coupon::where("classe_id",session("classe_id")[0] ?? 1)
                                                         ->where("semestre_id",session("semestre_id")[0] ?? 1)->count())
                ->icon("heroicon-o-calendar-days"),


            ];

    }
}
