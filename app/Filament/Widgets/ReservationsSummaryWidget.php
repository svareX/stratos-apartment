<?php

namespace App\Filament\Widgets;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class ReservationsSummaryWidget extends Widget
{
    protected string $view = 'filament.widgets.reservations-summary-widget';

    protected int|string|array $columnSpan = 'full';

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    public function getViewData(): array
    {
        $today = Carbon::today();
        $periodStart = $today->copy()->subDays(30);

        $total = Reservation::count();
        $last30 = Reservation::whereBetween('created_at', [$periodStart, $today])->count();
        $revenue30 = Reservation::whereBetween('created_at', [$periodStart, $today])->sum('price');
        $confirmed = Reservation::where('status', ReservationStatus::Confirmed)->count();

        return compact('total', 'last30', 'revenue30', 'confirmed');
    }
}
