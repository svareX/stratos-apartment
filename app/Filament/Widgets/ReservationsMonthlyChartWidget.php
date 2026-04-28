<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ReservationsMonthlyChartWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'reservationsMonthlyChart';

    protected static ?string $heading = null;

    protected function getHeading(): ?string
    {
        return __('Reservations / Revenue — :year', ['year' => Carbon::now()->year]);
    }

    protected static ?int $contentHeight = 200;

    protected int|string|array $columnSpan = 'full';

    protected function getOptions(): array
    {
        $year = Carbon::now()->year;

        $labels = [];
        $counts = [];
        $revenue = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::create($year, $m, 1)->locale(app()->getLocale())->translatedFormat('M');
            $counts[] = Reservation::whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->count();
            $revenue[] = (float) Reservation::whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->sum('price');
        }

        return [
            'chart' => [
                'type' => 'line',
                'height' => 200,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                ['name' => __('Reservations'), 'data' => $counts],
                ['name' => __('Revenue (CZK)'), 'data' => $revenue],
            ],
            'stroke' => [
                'width' => [0, 3],
                'curve' => 'smooth',
            ],
            'markers' => [
                'size' => [0, 3],
            ],
            'plotOptions' => [
                'bar' => [
                    'columnWidth' => '48%',
                    'borderRadius' => 6,
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'xaxis' => [
                'categories' => $labels,
                'axisTicks' => ['show' => false],
                'axisBorder' => ['show' => false],
                'labels' => ['style' => ['colors' => '#94a3b8']],
            ],
            'yaxis' => [
                [
                    'seriesName' => __('Reservations'),
                    'title' => ['text' => __('Reservations'), 'style' => ['color' => '#94a3b8']],
                    'labels' => ['style' => ['colors' => '#94a3b8']],
                ],
                [
                    'seriesName' => __('Revenue (CZK)'),
                    'opposite' => true,
                    'title' => ['text' => __('Revenue (CZK)'), 'style' => ['color' => '#94a3b8']],
                    'labels' => ['style' => ['colors' => '#94a3b8']],
                ],
            ],
            'grid' => ['borderColor' => '#2a3441', 'strokeDashArray' => 4],
            'colors' => ['#f59e0b', '#38bdf8'],
            'tooltip' => ['theme' => 'dark'],
            'legend' => ['labels' => ['colors' => '#cbd5e1']],
        ];
    }
}
