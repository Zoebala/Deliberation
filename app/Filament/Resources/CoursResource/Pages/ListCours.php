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
            Actions\CreateAction::make()
                    ->label("Ajouter un cours")
                    ->icon("heroicon-o-book-open"),
                Action::make("Section")
                ->icon("heroicon-o-building-office")
                ->label("Choix Classe")
                ->modalSubmitActionLabel("Définir")
                ->form([
                    Select::make("semestre_id")
                    ->label("Semestre")
                    ->searchable()
                    ->required()
                    ->live()
                    ->options(Semestre::query()->pluck("lib","id"))
                    ->afterStateUpdated(function($state,Set $set){
                        $Semestre=Semestre::whereId($state)->get(["lib"]);
                        $set("semestre",$Semestre[0]->lib);

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

                        session()->push("semestre_id", $data["semestre_id"]);
                        session()->push("semestre", $data["semestre"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }else{
                        session()->pull("semestre_id");
                        session()->pull("semestre");
                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
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

        return Action::make("Section")
                ->modalHeading("Choix du Classe")
                ->modalSubmitActionLabel("Définir")
                ->visible(fn():bool => session("semestre_id") == null)
                ->form([
                    Select::make("semestre_id")
                    ->label("Semestre")
                    ->searchable()
                    ->required()
                    ->live()
                    ->options(Semestre::query()->pluck("lib","id"))
                    ->afterStateUpdated(function($state,Set $set){
                        $Semestre=Semestre::whereId($state)->get(["lib"]);
                        $set("semestre",$Semestre[0]->lib);

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

                        session()->push("semestre_id", $data["semestre_id"]);
                        session()->push("semestre", $data["semestre"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }else{
                        session()->pull("semestre_id");
                        session()->pull("semestre");
                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
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

        $Classe=Classe::whereId(session("classe_id")[0] ?? 1)->first();

            return [
                " $Classe->lib | $Semestre->lib"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("cours.classe_id",session("classe_id")[0] ?? 1);

                })->badge(Cours::join("classes","classes.id","cours.classe_id")
                                ->where("classes.id",session("classe_id")[0] ?? 1)
                                 ->count())
                ->icon("heroicon-o-building-office-2"),
                'Tous'=>Tab::make()
                ->badge(Cours::query()->count()),

            ];

    }
}
