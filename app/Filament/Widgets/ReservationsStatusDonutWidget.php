<?php

namespace App\Filament\Widgets;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ReservationsStatusDonutWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'reservationsStatusDonut';

    protected static ?string $heading = null;

    protected static ?int $contentHeight = 240;

    protected int|string|array $columnSpan = [
        'xl' => 4,
    ];

    protected function getOptions(): array
    {
        $groups = Reservation::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $series = array_values($groups);
        $labels = array_map(
            fn (string $status): string => ReservationStatus::tryFrom($status)?->label() ?? ucfirst($status),
            array_keys($groups)
        );

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 240,
                'toolbar' => ['show' => false],
            ],
            'series' => $series,
            'labels' => $labels,
            'colors' => ['#f59e0b', '#22c55e', '#ef4444', '#38bdf8', '#a855f7'],
            'dataLabels' => ['enabled' => false],
            'stroke' => ['show' => true, 'width' => 2, 'colors' => ['#0f172a']],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '62%',
                        'labels' => [
                            'show' => true,
                            'value' => ['color' => '#e2e8f0', 'fontSize' => '20px'],
                            'total' => [
                                'show' => true,
                                'label' => __('Total'),
                                'color' => '#94a3b8',
                            ],
                        ],
                    ],
                ],
            ],
            'legend' => [
                'position' => 'bottom',
                'labels' => ['colors' => '#cbd5e1'],
            ],
            'tooltip' => ['theme' => 'dark'],
        ];
    }

    public function getHeading(): ?string
    {
        return __('Reservation Status');
    }
}
