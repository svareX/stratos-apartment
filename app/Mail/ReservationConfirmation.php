<?php

namespace App\Mail;

use App\Models\Reservation;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
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
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Reservation Confirmation').' - '.config('app.name', 'Apartmán Stratos'),
        );
    }

    public function content(): Content
    {
        $eurAmount = $this->calculateEurAmount();

        return new Content(
            markdown: 'emails.reservation.confirmation',
            with: [
                'qrCodeCzk' => $this->generateSpaydQrCode(),
                'qrCodeEur' => $this->generateEpcQrCode($eurAmount),
                'eurAmount' => $eurAmount,
            ],
        );
    }

    private function calculateEurAmount(): float
    {
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

        return round($this->reservation->price / $rate, 2);
    }

    private function generateQrCode(string $content): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new ImagickImageBackEnd
        );

        $writer = new Writer($renderer);

        return $writer->writeString($content);
    }

    private function generateSpaydQrCode(): string
    {
        $amount = number_format($this->reservation->price, 2, '.', '');
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
