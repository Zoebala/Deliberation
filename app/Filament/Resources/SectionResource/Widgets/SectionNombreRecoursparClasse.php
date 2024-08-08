<?php

namespace App\Filament\Resources\SectionResource\Widgets;

use App\Models\Classe;
use Filament\Widgets\ChartWidget;

class SectionNombreRecoursparClasse extends ChartWidget
{
    protected static ?string $heading = 'Nombre Recours par Classe';
    protected static bool $isLazy = false;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '270px';

    protected function getData(): array
    {
        $Classes=Classe::get("lib");
        $tableau=[];$ClasseId=[];$Effectifparclasse=[];
        //mise des valeurs de l'objet dans la variable tableau
        foreach ($Classes as $Classe) {
            $tableau[]=$Classe->lib;
        }


        $Classes=Classe::get(["lib","id"]);
        //récupération des clefs de Classes
        foreach ($Classes as $Classe){
            $ClasseId[]=$Classe->id;
        }

        foreach($ClasseId as $index){

            $Effectifparclasse[]=Classe::join("recours","recours.classe_id","classes.id")
                                    ->join("annees","annees.id","recours.annee_id")
                                    ->join("juries","juries.id","classes.jury_id")
                                    ->where("annees.id",session('Annee_id')[0] ?? 1)
                                    ->where("section_id",session('section_id')[0] ?? 1)
                                    ->where("classes.id",$index)
                                    ->count();
        }




        return [
            'datasets' => [
                [
                    'label' => 'Nombre Recours par Classe',
                    'data' => $Effectifparclasse,
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
