<?php

namespace App\Filament\Resources\EtudiantResource\Pages;

use Filament\Actions;
use App\Models\Classe;
use Filament\Forms\Set;
use App\Models\Etudiant;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EtudiantResource;
use Filament\Resources\Pages\ListRecords\Tab;
use Konnco\FilamentImport\Actions\ImportField;
use Konnco\FilamentImport\Actions\ImportAction;

class ListEtudiants extends ListRecords
{
    protected static string $resource = EtudiantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Ajouter Etudiant")
            ->icon("heroicon-o-user-plus"),
             Action::make("classe_choix")
                ->icon("heroicon-o-building-office")
                ->label("Choix de la Classe")
                ->modalSubmitActionLabel("Définir")
                ->form([
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
                    if(session('classe_id')==NULL && session('classe')==NULL){

                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }else{

                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);


                    }
                    Notification::make()
                    ->title("Classe Choisie :  ".$data['classe'])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.etudiants.index");

                }),
            ImportAction::make("importation")
                    ->label("Importer Etudiants")
                    ->icon("heroicon-o-document-arrow-down")
                    ->fields([
                        ImportField::make('nom')
                            ->required(),
                        ImportField::make('postnom')
                            ->required()
                            ->label('Postnom'),
                        ImportField::make('prenom')
                            ->required()
                            ->label('Prenom'),
                        ImportField::make('genre')
                            ->required()
                            ->label('Genre'),
                        ImportField::make('classe_id')
                            ->required()
                            ->label('Classe'),

                    ])
        ];
    }

    public $defaultAction="classe";
    public function classe():Action
    {

        return Action::make("classe")
                ->modalHeading("Choix de la Classe")
                ->modalSubmitActionLabel("Définir")
                ->visible(fn():bool => session("classe_id") == null)
                ->form([
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
                    if(session('classe_id')==NULL && session('classe')==NULL){

                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);

                    }else{

                        session()->pull("classe_id", $data["classe_id"]);
                        session()->pull("classe", $data["classe"]);
                        session()->push("classe_id", $data["classe_id"]);
                        session()->push("classe", $data["classe"]);


                    }
                    Notification::make()
                    ->title("Classe Choisie :  ".$data['classe'])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.etudiants.index");

                });

    }

    public function getTabs():array
    {

        $Classe=Classe::where("id",session("classe_id")[0] ?? 1)->first();

            return [
                "$Classe->lib"=>Tab::make()
                ->modifyQueryUsing(function(Builder $query)
                {
                $query->where("classe_id",session("classe_id")[0] ?? 1);

                })->badge(Etudiant::where("classe_id",session("classe_id")[0] ?? 1)
                                 ->count())
                ->icon("heroicon-o-calendar-days"),
                'Tous'=>Tab::make()
                ->badge(Etudiant::query()->count()),

            ];

    }
}
