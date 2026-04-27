<?php

namespace App\Filament\Resources\Reservations\Pages;

use App\Filament\Resources\Reservations\ReservationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;


    public function getTabs(): array
    {
        $start = Carbon::now()->addMonth()->startOfMonth()->toDateString();
        $end = Carbon::now()->addMonth()->endOfMonth()->toDateString();

        return [
            __('All') => Tab::make(),

            __('Next month') => Tab::make()
                ->modifyQueryUsing(static function (Builder $query) use ($start, $end) {
                    $query->whereBetween('check_in', [$start, $end]);
                }),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
