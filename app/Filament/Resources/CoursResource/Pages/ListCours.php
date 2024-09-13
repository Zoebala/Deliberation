<?php

namespace App\Filament\Resources\CoursResource\Pages;

use App\Models\Cours;
use Filament\Actions;
use App\Models\Classe;
use Filament\Forms\Set;
use App\Models\Semestre;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Filament\Resources\CoursResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;

class ListCours extends ListRecords
{
    protected static string $resource = CoursResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),
            Actions\CreateAction::make()
                    ->label("Ajouter un cours")
                    ->icon("heroicon-o-book-open")
                    ->hidden(fn():bool => session("classe_id") == null),
                Action::make("Section")
                ->icon("heroicon-o-building-office")
                ->label("Choix Classe")
                ->slideOver()
                ->modalSubmitActionLabel("Définir")
                ->form([
                    Select::make("semestre_id")
                    ->label("Semestre")
                    ->searchable()
                    ->required()
                    ->live()
                    ->options(Semestre::query()->pluck("lib","id"))
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Semestre=Semestre::whereId($state)->get(["lib"]);
                            $set("semestre",$Semestre[0]->lib);
                        }

                    }),
                    Hidden::make("semestre")
                    ->disabled()
                    ->dehydrated(true),
                    Select::make("classe_id")
                    ->label("Classe")
                    ->options(Classe::all()->pluck("lib","id"))
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

                    if(session('semestre_id')==NULL && session('semestre')==NULL){

                        session()->push("semestre_id", $data["semestre_id"]);
                        session()->push("semestre", $data["semestre"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }else{

                        session()->pull("semestre_id");
                        session()->pull("semestre");
                        session()->pull("classe_id");
                        session()->pull("classe");
                        session()->push("semestre_id", $data["semestre_id"]);
                        session()->push("semestre", $data["semestre"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);


                    }

                    Notification::make()
                    ->title("Classe Choisie :  ".$data['classe']." | ". $data["semestre"])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.cours.index");

                }),
        ];
    }

    public $defaultAction="classe";

    public function classe():Action
    {

        return Action::make("classe")
                ->modalHeading("Choix de la Classe")
                ->modalSubmitActionLabel("Définir")
                ->slideOver()
                ->visible(fn():bool => session("classe_id") == null)
                ->form([
                    Select::make("semestre_id")
                    ->label("Semestre")
                    ->searchable()
                    ->required()
                    ->live()
                    ->options(Semestre::query()->pluck("lib","id"))
                    ->afterStateUpdated(function($state,Set $set){
                        if($state){
                            $Semestre=Semestre::whereId($state)->get(["lib"]);
                            $set("semestre",$Semestre[0]->lib);
                        }

                    }),
                    Hidden::make("semestre")
                    ->disabled()
                    ->dehydrated(true),
                    Select::make("classe_id")
                    ->label("Classe")
                    ->options(Classe::all()->pluck("lib","id"))
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

                        session()->push("semestre_id", $data["semestre_id"]);
                        session()->push("semestre", $data["semestre"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }else{
                        session()->pull("semestre_id");
                        session()->pull("semestre");
                        session()->pull("classe_id");
                        session()->pull("classe");
                        session()->push("semestre_id", $data["semestre_id"]);
                        session()->push("semestre", $data["semestre"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);


                    }
                    Notification::make()
                    ->title("Classe Choisie :  ".$data['classe']." | ". $data["semestre"])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.cours.index");

                });

    }


    public function getTabs():array
    {

        $Semestre=Semestre::where("id",session("semestre_id")[0] ?? 1)->first();

        $LibSemestre=$Semestre ? $Semestre->lib:"Veuillez choisir un semestre";
        $Classe=Classe::whereId(session("classe_id")[0] ?? 1)->first();
        $LibClasse=$Classe ? $Classe->lib:"Veuillez choisir une classe";

            return [
                " $LibClasse | $LibSemestre"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("cours.classe_id",session("classe_id")[0] ?? 1)->where("semestre_id",session("semestre_id")[0] ?? 1);

                })->badge(Cours::join("classes","classes.id","cours.classe_id")
                                ->where("classes.id",session("classe_id")[0] ?? 1)
                                ->where("semestre_id",session("semestre_id")[0] ?? 1)
                                 ->count())
                ->icon("heroicon-o-building-office-2"),


            ];

    }
}
