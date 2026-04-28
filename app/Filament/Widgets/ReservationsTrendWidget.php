<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ReservationsTrendWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'reservationsTrendChart';

    protected static ?int $contentHeight = 300;

    protected int|string|array $columnSpan = [
        'xl' => 8,
    ];

    public function getHeading(): ?string
    {
        return __('Reservations — Last 30 days');
    }

    protected function getOptions(): array
    {
        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $labels[] = $day->locale(app()->getLocale())->translatedFormat('M d');
            $data[] = Reservation::whereDate('created_at', $day)->count();
        }

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => __('Reservations'),
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
                'labels' => [
                    'style' => [
                        'colors' => array_fill(0, count($labels), '#cbd5e1'),
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 3,
            ],
            'colors' => ['#f59e0b'],
            'tooltip' => [
                'theme' => 'dark',
            ],
        ];
    }
}
