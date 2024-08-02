<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use Filament\Widgets\ChartWidget;

class Effectifjuryparsection extends ChartWidget
{
    protected static ?string $heading = 'Effectif Jury par Section';
    protected static bool $isLazy = false;
    protected static ?int $sort = 20;

    protected function getData(): array
    {
        $Sections=Section::get("lib");
        $tableau=[];$SectionId=[];$EffectifparSection=[];
        //mise des valeurs de l'objet dans la variable tableau
        foreach ($Sections as $Section) {
            $tableau[]=$Section->lib;
        }


        $Sections=Section::get(["lib","id"]);
        //récupération des clefs de Sections
        foreach ($Sections as $Section){
            $SectionId[]=$Section->id;
        }

        foreach($SectionId as $index){

            $EffectifparSection[]=Section::join("juries","juries.section_id","sections.id")
                                         ->join("annees","annees.id","juries.annee_id")
                                         ->where("annees.id",session('Annee_id') ?? 1)
                                         ->where("juries.section_id",$index)
                                         ->count();
        }


        // dd(session('AnneeDebut'));




        return [
            'datasets' => [
                [
                    'label' => 'Effectif Jury par Section',
                    'data' => $EffectifparSection,
                    // définition des couleurs pour les effectifs des sections
                    'backgroundColor' => [
                        'rgb(255,99,132)',
                        'rgb(54,162,235)',
                        'rgb(255,205,86)',
                        'red',
                        'gray',
                        'green',
                        'yellow',
                        'lightblue',

                    ],
                ],
            ],
            'labels' => $tableau,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
