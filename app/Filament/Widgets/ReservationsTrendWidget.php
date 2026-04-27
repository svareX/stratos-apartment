<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ReservationsTrendWidget extends ChartWidget
{
    protected ?string $heading = null;

    protected int|string|array $columnSpan = [
        'xl' => 8,
    ];

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): ?string
    {
        return __('Reservations — Last 30 days');
    }

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $labels[] = $day->locale(app()->getLocale())->translatedFormat('M d');
            $data[] = Reservation::whereDate('created_at', $day)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => __('Reservations'),
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.18)',
                    'fill' => true,
                    'tension' => 0.35,
                    'pointRadius' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'ticks' => [
                        'color' => '#94a3b8',
                        'maxTicksLimit' => 10,
                        'autoSkip' => true,
                        'maxRotation' => 0,
                    ],
                    'grid' => ['display' => false],
                ],
                'y' => [
                    'ticks' => ['color' => '#94a3b8'],
                    'grid' => ['color' => 'rgba(148, 163, 184, 0.14)'],
                ],
            ],
        ];
    }
}
