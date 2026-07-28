<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KodeOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $kodeOtp,
        public string $namaTujuan,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi (OTP) — Portal Desa Karduluk',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.kode-otp',
            with: [
                'kodeOtp' => $this->kodeOtp,
                'nama' => $this->namaTujuan,
            ],
        );
    }
}
