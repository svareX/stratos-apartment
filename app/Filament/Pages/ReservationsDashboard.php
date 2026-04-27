<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ReservationCalendarWidget;
use App\Filament\Widgets\ReservationsByWeekdayWidget;
use App\Filament\Widgets\ReservationsMonthlyChartWidget;
use App\Filament\Widgets\ReservationsOverviewStatsWidget;
use App\Filament\Widgets\ReservationsPerApartmentWidget;
use App\Filament\Widgets\ReservationsStatusDonutWidget;
use App\Filament\Widgets\ReservationsTrendWidget;
use BackedEnum;
use Filament\Pages\Dashboard;

class ReservationsDashboard extends Dashboard
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $title = null;

    protected static ?string $slug = 'reservations-dashboard';

    protected static ?int $navigationSort = 1;

    public function getTitle(): string
    {
        return __('Reservations Dashboard');
    }

    public function getWidgets(): array
    {
        return [
            ReservationsOverviewStatsWidget::class,
            ReservationsTrendWidget::class,
            ReservationsStatusDonutWidget::class,
            ReservationsMonthlyChartWidget::class,
            ReservationsPerApartmentWidget::class,
            ReservationsByWeekdayWidget::class,
            ReservationCalendarWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 12,
        ];
    }
}
