<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use App\Models\Apartment;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ReservationsPerApartmentWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'reservationsPerApartment';

    protected static ?string $heading = null;

    protected static ?int $contentHeight = 300;

    protected int|string|array $columnSpan = [
        'xl' => 6,
    ];

    protected function getOptions(): array
    {
        $rows = Reservation::select('apartment_id', \DB::raw('count(*) as total'))
            ->groupBy('apartment_id')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $labels = [];
        $data = [];
        foreach ($rows as $row) {
            $apt = Apartment::find($row->apartment_id);
            $labels[] = $apt?->name ?? ('#' . $row->apartment_id);
            $data[] = (int) $row->total;
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'toolbar' => ['show' => false],
            ],
            'series' => [['name' => __('Reservations'), 'data' => $data]],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                    'barHeight' => '55%',
                    'borderRadius' => 6,
                ],
            ],
            'dataLabels' => ['enabled' => false],
            'xaxis' => [
                'categories' => $labels,
                'labels' => ['style' => ['colors' => '#94a3b8']],
            ],
            'yaxis' => [
                'labels' => ['style' => ['colors' => '#cbd5e1']],
            ],
            'grid' => ['borderColor' => '#2a3441', 'strokeDashArray' => 4],
            'colors' => ['#f59e0b'],
            'tooltip' => ['theme' => 'dark'],
        ];
    }

    public function getHeading(): ?string
    {
        return __('Top Apartments');
    }
}

