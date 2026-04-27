<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ReservationsByWeekdayWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'reservationsByWeekday';

    protected static ?string $heading = null;

    protected static ?int $contentHeight = 300;

    protected int|string|array $columnSpan = [
        'xl' => 6,
    ];

    protected function getOptions(): array
    {
        $weekdayOrder = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $counts = array_fill_keys($weekdayOrder, 0);

        Reservation::query()
            ->select('check_in')
            ->whereNotNull('check_in')
            ->get()
            ->each(function (Reservation $reservation) use (&$counts): void {
                $label = Carbon::parse($reservation->check_in)->format('D');
                if (array_key_exists($label, $counts)) {
                    $counts[$label]++;
                }
            });

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                ['name' => __('Check-ins'), 'data' => array_values($counts)],
            ],
            'plotOptions' => [
                'bar' => [
                    'columnWidth' => '44%',
                    'borderRadius' => 6,
                ],
            ],
            'dataLabels' => ['enabled' => false],
            'xaxis' => [
                'categories' => array_map(fn (string $day): string => __($day), array_keys($counts)),
                'axisTicks' => ['show' => false],
                'axisBorder' => ['show' => false],
                'labels' => ['style' => ['colors' => '#94a3b8']],
            ],
            'yaxis' => [
                'labels' => ['style' => ['colors' => '#94a3b8']],
            ],
            'grid' => ['borderColor' => '#2a3441', 'strokeDashArray' => 4],
            'colors' => ['#38bdf8'],
            'tooltip' => ['theme' => 'dark'],
        ];
    }

    public function getHeading(): ?string
    {
        return __('Check-ins by Weekday');
    }
}
