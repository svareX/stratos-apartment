<?php

namespace App\Mail;

use App\Models\Reservation;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ReservationConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation->loadMissing(['apartment', 'apartmentPackage']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Reservation Confirmation').' - '.config('app.name', 'Apartmán Stratos'),
        );
    }

    public function content(): Content
    {
        $eur = $this->calculateEurAmount();

        return new Content(
            markdown: 'emails.reservation.confirmation',
            with: [
                'qrCodeCzk' => $this->generateSpaydQrCode(),
                'qrCodeEur' => $this->generateEpcQrCode($eur['amount']),
                'eurAmount' => $eur['amount'],
                'eurIsConverted' => $eur['converted'],
            ],
        );
    }

    private function calculateEurAmount()
    {
        $apt = $this->reservation->apartment;
        /** @var \App\Models\Apartment|null $apt */
        $rate = Cache::remember('cnb_eur_rate', 3600, function () {
            $response = Http::get('https://www.cnb.cz/cs/financni-trhy/devizovy-trh/kurzy-devizoveho-trhu/kurzy-devizoveho-trhu/denni_kurz.txt');

            if ($response->successful()) {
                $lines = explode("\n", $response->body());
                foreach ($lines as $line) {
                    if (str_contains($line, 'EMU|euro|1|EUR')) {
                        $parts = explode('|', $line);

                        return (float) str_replace(',', '.', $parts[4]);
                    }
                }
            }

            return 25.00;
        });

        $usedConverted = false;

        if ($apt && $apt->base_price_eur !== null) {
            $nights = Carbon::parse($this->reservation->check_in)->diffInDays(Carbon::parse($this->reservation->check_out));
            $totalEur = $nights * $apt->base_price_eur;

            $pkg = $this->reservation->apartmentPackage;
            /** @var \App\Models\ApartmentPackage|null $pkg */
            if ($pkg && $pkg->price_eur !== null) {
                $totalEur += $pkg->price_eur;
            } elseif (! empty($this->reservation->package_price)) {
                $totalEur += round($this->reservation->package_price / $rate, 2);
                $usedConverted = true;
            }

            if ($nights > 0 && $nights <= ($apt->days_for_cleaning_fee ?? 0)) {
                if ($apt->cleaning_fee_eur !== null) {
                    $totalEur += $apt->cleaning_fee_eur;
                } else {
                    $totalEur += ($apt->cleaning_fee ?? 0) / $rate;
                    $usedConverted = true;
                }
            }

            return ['amount' => round($totalEur, 2), 'converted' => $usedConverted];
        }

        return ['amount' => round($this->reservation->price / $rate, 2), 'converted' => true];
    }

    private function generateQrCode(string $content): string
    {
        try {
            if (extension_loaded('imagick') && class_exists(ImagickImageBackEnd::class)) {
                $backend = new ImagickImageBackEnd;
            } elseif (class_exists(\BaconQrCode\Renderer\Image\SvgImageBackEnd::class)) {
                $backend = new \BaconQrCode\Renderer\Image\SvgImageBackEnd;
            } elseif (class_exists(\BaconQrCode\Renderer\Image\GdImageBackEnd::class)) {
                $backend = new \BaconQrCode\Renderer\Image\GdImageBackEnd;
            } else {
                return '';
            }

            $renderer = new ImageRenderer(new RendererStyle(200), $backend);
            $writer = new Writer($renderer);

            return $writer->writeString($content);
        } catch (\Throwable $e) {
            return '';
        }
    }

    private function generateSpaydQrCode(): string
    {
        $amount = number_format((float) $this->reservation->price, 2, '.', '');
        $iban = config('services.bank.iban');
        $ref = 'RES'.$this->reservation->id;

        $spaydString = sprintf(
            'SPD*1.0*ACC:%s*AM:%s*CC:CZK*MSG:%s',
            $iban,
            $amount,
            $ref
        );

        return $this->generateQrCode($spaydString);
    }

    private function generateEpcQrCode(float $eurAmount): string
    {
        $amount = number_format($eurAmount, 2, '.', '');
        $iban = config('services.bank.iban');
        $bic = config('services.bank.bic');
        $name = config('services.bank.name');
        $ref = 'RES'.$this->reservation->id;

        $qrContent = [
            'BCD',
            '002',
            '1',
            'SCT',
            $bic,
            $name,
            $iban,
            'EUR'.$amount,
            '',
            '',
            $ref,
            '',
        ];

        return $this->generateQrCode(implode("\n", $qrContent));
    }
}
