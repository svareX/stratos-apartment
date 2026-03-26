<x-mail::message>
# {{ __('Reservation Update') }}

{{ __('Hello') }} **{{ $reservation->user->name ?? __('Guest') }}**,

{{ __('The status of your reservation has been updated.') }}

<x-mail::panel>
**{{ __('New Status') }}:** {{ $reservation->status instanceof \App\Enums\ReservationStatus ? $reservation->status->label() : \App\Enums\ReservationStatus::from($reservation->status)->label() }}  
**{{ __('Apartment') }}:** {{ $reservation->apartment->name }}  
**{{ __('Check-in') }}:** {{ \Carbon\Carbon::parse($reservation->check_in)->format('d. m. Y') }}  
**{{ __('Check-out') }}:** {{ \Carbon\Carbon::parse($reservation->check_out)->format('d. m. Y') }}  
**{{ __('Total price') }}:** {{ number_format($reservation->price, 0, ',', ' ') }} {{ __('CZK') }}
</x-mail::panel>

**{{ __('Location') }}:** {{ $reservation->apartment->address }}

<x-mail::button :url="'http://maps.google.com/maps?q=' . urlencode($reservation->apartment->address)">
{{ __('Navigate to Apartment') }}
</x-mail::button>

{{ __('If you have any questions, feel free to reply directly to this email.') }}

{{ __('Best regards,') }}<br>
{{ config('app.name', 'Apartmán Stratos') }}
</x-mail::message>