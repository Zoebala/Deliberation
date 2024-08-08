<?php

namespace App\Filament\Resources\SectionResource\Pages;

use Filament\Actions;
use App\Models\Section;
use Filament\Forms\Set;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\SectionResource;
use App\Filament\Resources\SectionResouceResource\Widgets\StatSectionOverview;
use App\Filament\Resources\SectionResource\Widgets\SectionNombreRecoursparJury;
use App\Filament\Resources\SectionResource\Widgets\SectionEffectifClasseparJury;
use App\Filament\Resources\SectionResource\Widgets\SectionNombreRecoursparClasse;

class ListSections extends ListRecords
{
    protected static string $resource = SectionResource::class;
    protected static bool $isLazy = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make("Accueil")
            ->icon("heroicon-o-home")
            ->action(function(){
                return redirect("/");
            }),
            Actions\CreateAction::make()
            ->label("Nouvelle Section/faculté")
            ->icon("heroicon-o-building-office-2"),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            StatSectionOverview::class,
            SectionEffectifClasseparJury::class,
            SectionNombreRecoursparJury::class,
            SectionNombreRecoursparClasse::class,
        ];
    }

    protected function getWidgets():array
    {
        return [
            StatSectionOverview::class,
            SectionEffectifClasseparJury::class,
            SectionNombreRecoursparJury::class,
            SectionNombreRecoursparClasse::class,
        ];
    }

    public $defaultAction="section";

    public function section():Action
    {

        return Action::make("Section")
                ->modalHeading("Choix du jury")
                ->modalSubmitActionLabel("Définir")
                ->visible(fn():bool => session("section_id") == null)
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


                ])
                ->modalWidth(MaxWidth::Medium)
                ->modalIcon("heroicon-o-building-office-2")
                ->action(function(array $data){
                    if(session('section_id')==NULL && session('section')==NULL){

                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);

                    }else{
                        session()->pull("section_id");
                        session()->pull("section");
                        session()->push("section_id", $data["section_id"]);
                        session()->push("section", $data["section"]);


                    }

                    // dd(session('Annee'));
                    Notification::make()
                    ->title("Section Choisie :  ".$data['section'])
                    ->success()
                     ->duration(5000)
                    ->send();
                     return redirect()->route("filament.admin.resources.sections.index");

                });

    }

}
