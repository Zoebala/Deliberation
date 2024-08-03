<?php

namespace App\Filament\Widgets;

use App\Models\Jury;
use App\Models\Classe;
use Filament\Widgets\ChartWidget;

class Effectifclasseparjury extends ChartWidget
{
    protected static ?string $heading = 'Effectif classe par Jury';

    protected static bool $isLazy = false;
    protected static ?int $sort = 30;

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
        $Juries=Jury::get("lib");
        $tableau=[];$JuryId=[];$Effectifparjury=[];
        //mise des valeurs de l'objet dans la variable tableau
        foreach ($Juries as $Jury) {
            $tableau[]=$Jury->lib;
        }


        $Juries=Jury::get(["lib","id"]);
        //récupération des clefs de Juries
        foreach ($Juries as $Jury){
            $JuryId[]=$Jury->id;
        }

        foreach($JuryId as $index){

            $Effectifparjury[]=Classe::join("juries","juries.id","classes.jury_id")
                                         ->join("annees","annees.id","juries.annee_id")
                                         ->where("annees.id",session('Annee_id') ?? 1)
                                         ->where("juries.id",$index)
                                         ->count();
        }


        // dd(session('AnneeDebut'));




        return [
            'datasets' => [
                [
                    'label' => 'Effectif classe par Jury',
                    'data' => $Effectifparjury,
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
