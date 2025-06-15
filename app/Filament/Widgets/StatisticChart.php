<?php

namespace App\Filament\Widgets;

use App\Models\Statistic;
use Filament\Widgets\ChartWidget;

class StatisticChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Event per Hari';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $data = Statistic::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Event',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->pluck('date')->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M')),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bisa diganti dengan 'line' atau 'pie'
    }
}
