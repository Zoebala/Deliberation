<?php

namespace App\Filament\Pages;

use App\Models\Annee;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Pages\Dashboard;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class CustomDashboard extends Dashboard
{
    protected function getHeaderActions():array
    {
        return [
            Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),
            Action::make("annee_choix")
            ->icon("heroicon-o-calendar")
            // ->visible(fn():bool => session("Annee_id")==null)
            ->slideOver()
            ->label("Choix année de travail")
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
    public $defaultAction="annee";

    public function annee():Action
    {
        return Action::make("annee")
        ->icon("heroicon-o-calendar")
        ->visible(fn():bool => session("Annee_id")==null)
        ->slideOver()
        ->label("Choix année de travail")
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


        });
    }

}
