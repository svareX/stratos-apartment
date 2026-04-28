<x-mail::message>
    @if ($logoPath)
        <div style="text-align: center; margin-bottom: 30px">
            <img
                src="{{ $message->embed(public_path('images/logo/icon-big.png')) }}"
                alt="{{ config('app.name', 'Apartmán Stratos') }}"
                style="max-width: 200px; height: auto"
            />
        </div>
    @endif

    # {{ 'Reservation Update' }}

    {{ 'Hello' }} **{{ $reservation->user->name ?? 'Guest' }}**,

    {{ 'The status of your reservation has been updated.' }}

    <x-mail::panel>
        **{{ 'New Status' }}:** {{ $reservation->status instanceof \App\Enums\ReservationStatus ? $reservation->status->label() : \App\Enums\ReservationStatus::from($reservation->status)->label() }}<br />
        **{{ 'Apartment' }}:** {{ $reservation->apartment->name }}<br />
        **{{ 'Check-in' }}:** {{ \Carbon\Carbon::parse($reservation->check_in)->format('d. m. Y') }}<br />
        **{{ 'Check-out' }}:** {{ \Carbon\Carbon::parse($reservation->check_out)->format('d. m. Y') }}<br />
        **{{ 'Total price' }}:** {{ number_format($reservation->price, 0, ',', ' ') }} {{ 'CZK' }}
    </x-mail::panel>

    **{{ 'Location' }}:** {{ $reservation->apartment->address }}

    <x-mail::button
        :url="'https://www.google.com/maps/search/?api=1&query=' . urlencode($reservation->apartment->address)"
    >
        {{ 'Navigation to the Apartment' }}
    </x-mail::button>

    {{ 'If you have any questions, feel free to reply directly to this email.' }}

    {{ 'Best regards,' }}<br />
    {{ config('app.name', 'Apartmán Stratos') }}
</x-mail::message>
