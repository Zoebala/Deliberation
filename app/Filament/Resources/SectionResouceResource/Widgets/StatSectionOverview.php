<?php

namespace App\Filament\Resources\SectionResouceResource\Widgets;

use App\Models\Jury;
use App\Models\Recours;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatSectionOverview extends BaseWidget
{

    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            //
            Stat::make("Effectif Jury", function(){
                if(session("section_id")){

                    return Jury::where("section_id",session("section_id")[0] ?? 1)
                                ->where("annee_id",session("Annee_id")[0] ?? 1)->count();
                }
                 return Jury::where("annee_id",session("Annee_id")[0] ?? 1)->count();
            })
            ->description(session("section")[0] ?? "Nos jurys")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-building-library"),
            Stat::make("Recours",function(){
                if(session("classe_id")){

                    return Recours::where("annee_id",session("Annee_id")[0] ?? 1)
                                    ->where("classe_id",session("classe_id")[0] ?? 1)->count();
                }
               return Recours::where("annee_id",session("Annee_id")[0] ?? 1)->count();
            })
            ->description(session("classe")[0] ?? "Nos recours")
            ->color("danger")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-document-text"),

        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}
