<x-mail::message>
# {{ __('Reservation Confirmation') }}

{{ __('Hello') }} **{{ $reservation->user->name }}**,

{{ __('Thank you for your reservation! We are thrilled to host you.') }}

<x-mail::panel>
**{{ __('Apartment') }}:** {{ $reservation->apartment->name }}  
**{{ __('Check-in') }}:** {{ \Carbon\Carbon::parse($reservation->check_in)->format('d. m. Y') }}  
**{{ __('Check-out') }}:** {{ \Carbon\Carbon::parse($reservation->check_out)->format('d. m. Y') }}  
**{{ __('Total price') }}:** {{ number_format($reservation->price, 0, ',', ' ') }} {{ __('CZK') }}
</x-mail::panel>

**{{ __('Location') }}:** {{ $reservation->apartment->address }}

<x-mail::button :url="'https://www.google.com/maps/search/?api=1&query=' . urlencode($reservation->apartment->address)">
{{ __('Navigate to Apartment') }}
</x-mail::button>

{{ __('We will contact you shortly with further details regarding your arrival. If you have any questions in the meantime, feel free to reply directly to this email.') }}

{{ __('Best regards,') }}<br>
{{ config('app.name', 'Apartmán Stratos') }}
</x-mail::message>