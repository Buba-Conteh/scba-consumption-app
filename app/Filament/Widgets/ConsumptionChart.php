<?php

namespace App\Filament\Widgets;

use App\Models\Consumption;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
class ConsumptionChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static ?int $sort = 2;
    public ?string $filter = 'today';

    protected function getData(): array
    {

        $activeFilter = $this->filter;
        $data = Trend::model(Consumption::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->average('consumption_rate');

        return [
            'datasets' => [
                [
                    'label' => 'Consumption',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
        
        
    }
    protected function getFilters(): ?array
{
    return [
        'today' => 'Today',
        'week' => 'Last week',
        'month' => 'Last month',
        'year' => 'This year',
    ];
}

    protected function getType(): string
    {
        return 'pie';
    }
}
