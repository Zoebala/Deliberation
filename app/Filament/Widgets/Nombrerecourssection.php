<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use Filament\Widgets\ChartWidget;

class Nombrerecourssection extends ChartWidget
{
    protected static ?string $heading = 'Nombre Recours par section';

    protected static bool $isLazy = false;
    protected static ?int $sort = 40;

    public static function canView(): bool
    {

        if(Auth()->user()->hasRole(["Admin"])){

            return true;
        }else{

            return false;
        }
    }

    protected function getData(): array
    {
        $Sections=Section::get("lib");
        $tableau=[];$SectionId=[];$Effectifparsection=[];
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

            $Effectifparsection[]=Section::join("juries","juries.section_id","sections.id")
                                         ->join("classes","classes.jury_id","juries.id")
                                         ->join("recours","recours.classe_id","classes.id")
                                         ->join("annees","annees.id","juries.annee_id")
                                         ->where("annees.id",session('Annee_id') ?? 1)
                                         ->where("sections.id",$index)
                                         ->count();
        }


        // dd(session('AnneeDebut'));




        return [
            'datasets' => [
                [
                    'label' => 'Nombre Recours par section',
                    'data' => $Effectifparsection,
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
        return 'doughnut';
    }
}
