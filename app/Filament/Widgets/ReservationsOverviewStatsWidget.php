<?php

namespace App\Filament\Widgets;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReservationsOverviewStatsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $today = Carbon::today();
        $periodStart = $today->copy()->subDays(30);

        $total = Reservation::count();
        $last30 = Reservation::whereBetween('created_at', [$periodStart, $today])->count();
        $revenue30 = Reservation::whereBetween('created_at', [$periodStart, $today])->sum('price');
        $confirmed = Reservation::where('status', ReservationStatus::Confirmed)->count();

        return [
            Stat::make(__('Total Reservations'), number_format($total)),
            Stat::make(__('Last 30 days'), number_format($last30)),
            Stat::make(__('Revenue (30d)'), '$'.number_format((float) $revenue30, 2)),
            Stat::make(__('Confirmed'), number_format($confirmed)),
        ];
    }
}
