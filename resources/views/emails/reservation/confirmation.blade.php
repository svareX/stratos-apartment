<x-mail::message>
<div style="text-align: center; margin-bottom: 30px;">
    <img src="{{ $message->embed(public_path('images/logo/icon-big.png')) }}" alt="{{ config('app.name', 'Apartmán Stratos') }}" style="max-width: 200px; height: auto;">
</div>

# {{ 'Reservation Confirmation' }}

{{ 'Hello' }} **{{ $reservation->user->name }}**,

{{ 'Thank you for your reservation! We are thrilled to host you.' }}

<x-mail::panel>
**{{ 'Apartment' }}:** {{ $reservation->apartment->name }}<br>
**{{ 'Check-in' }}:** {{ \Carbon\Carbon::parse($reservation->check_in)->format('d. m. Y') }}<br>
**{{ 'Check-out' }}:** {{ \Carbon\Carbon::parse($reservation->check_out)->format('d. m. Y') }}<br>
**{{ 'Package' }}:** @if($reservation->apartmentPackage) {{ $reservation->apartmentPackage->name }} @else {{ 'Standard' }} @endif<br>
**{{ 'Package price' }}:** {{ number_format($reservation->package_price ?? 0, 0, ',', ' ') }} {{ 'CZK' }}<br>
**{{ 'Total price' }}:** {{ number_format($reservation->price, 0, ',', ' ') }} {{ 'CZK' }}
</x-mail::panel>

<div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px; margin: 30px 0;">
    <table style="width: 100%; text-align: center; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding: 10px; border-right: 1px solid #e2e8f0;">
                <p style="margin-top: 0; margin-bottom: 15px; font-size: 15px; color: #475569;"><strong>Payment in CZK</strong></p>
                <img src="{{ $message->embedData($qrCodeCzk, 'qrcode-czk.png', 'image/png') }}" alt="QR CZK" style="max-width: 140px; height: auto; border-radius: 8px; border: 1px solid #cbd5e1; padding: 5px; background-color: #ffffff;">
                <p style="margin-top: 15px; margin-bottom: 0; font-size: 16px; font-weight: bold; color: #0f172a;">{{ number_format($reservation->price, 2, ',', ' ') }} CZK</p>
            </td>
            <td style="width: 50%; vertical-align: top; padding: 10px;">
                <p style="margin-top: 0; margin-bottom: 15px; font-size: 15px; color: #475569;"><strong>Payment in EUR</strong></p>
                <img src="{{ $message->embedData($qrCodeEur, 'qrcode-eur.png', 'image/png') }}" alt="QR EUR" style="max-width: 140px; height: auto; border-radius: 8px; border: 1px solid #cbd5e1; padding: 5px; background-color: #ffffff;">
                <p style="margin-top: 15px; margin-bottom: 0; font-size: 16px; font-weight: bold; color: #0f172a;">~ {{ number_format($eurAmount, 2, ',', ' ') }} EUR</p>
            </td>
        </tr>
    </table>
</div>

@if($reservation->apartmentPackage && count($reservation->apartmentPackage->translated_features ?? []))
<x-mail::panel>
**{{ 'Included package features' }}:**

@foreach($reservation->apartmentPackage->translated_features as $feature)
- {{ $feature }}
@endforeach

</x-mail::panel>
@endif

**{{ 'Location' }}:** {{ $reservation->apartment->address }}

<x-mail::button :url="'https://www.google.com/maps/search/?api=1&query=' . urlencode($reservation->apartment->address)">
{{ 'Navigation to the Apartment' }}
</x-mail::button>

{{ 'We will contact you shortly with further details regarding your arrival. If you have any questions in the meantime, feel free to reply directly to this email.' }}

{{ 'Best regards,' }}<br>
{{ config('app.name', 'Apartmán Stratos') }}
</x-mail::message>
