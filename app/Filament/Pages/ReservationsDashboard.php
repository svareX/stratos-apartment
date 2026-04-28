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
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;

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

    public static function getNavigationLabel(): string
    {
        return __('Dashboard');
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

    public function getActions(): array
    {
        return [
            Action::make('syncKnowledge')
                ->label(__('Sync knowledge base'))
                ->icon(Heroicon::ArrowPath)
                ->color('primary')
                ->action(function () {
                    dispatch(new \App\Jobs\SyncKnowledgeBaseJob);
                    Notification::make()->success()->title(__('Knowledge sync queued'))->send();
                }),

            Action::make('syncBooking')
                ->label(__('Sync booking (iCal)'))
                ->icon(Heroicon::ArrowPath)
                ->color('primary')
                ->action(function () {
                    dispatch(new \App\Jobs\SyncBookingJob);
                    Notification::make()->success()->title(__('Booking sync queued'))->send();
                }),
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
