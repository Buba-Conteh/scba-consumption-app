<?php

namespace App\Filament\Widgets;

use App\Models\Country;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
       
            return [
                Stat::make('Member Countries', Country::count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-flag')
                ->color('success'),
                Stat::make('Batch Trained', '30'),
                Stat::make('Average Score', '21%'),
            ];
      
    }
}
