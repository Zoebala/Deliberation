<?php

namespace App\Filament\Widgets;

use App\Models\Jury;
use App\Models\Recours;
use App\Models\Section;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatAdminOverview extends BaseWidget
{
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            //
            Stat::make("Sections/Facultés", Section::count())
            ->description("Nos Sections")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-building-office-2"),
            Stat::make("Jurys", Jury::count())
            ->description("Nos Jurys")
            ->color("success")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-building-library"),
            Stat::make("Recours", Recours::count())
            ->description("Nos Recours")
            ->color("danger")
            ->chart([34,2,5,23])
            ->Icon("heroicon-o-document-text"),
        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
}
