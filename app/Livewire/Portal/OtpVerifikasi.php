<?php

namespace App\Livewire\Portal;

use App\Models\DataKependudukan;
use App\Models\OtpCode;
use App\Models\User;
use App\Mail\KodeOtpMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.portal')]
#[Title('Verifikasi OTP')]
class OtpVerifikasi extends Component
{
    public string $kode_otp = '';

    public function mount(): void
    {
        // Tanpa data registrasi di session, tidak ada yang bisa diverifikasi.
        if (! session()->has('registrasi')) {
            $this->redirectRoute('registrasi', navigate: true);
        }
    }

    public function verifikasi(): void
    {
        $this->validate([
            'kode_otp' => ['required', 'digits:6'],
        ], [
            'kode_otp.digits' => 'Kode OTP terdiri dari 6 digit.',
        ]);

        $registrasi = session('registrasi');

        if (! $registrasi) {
            $this->redirectRoute('registrasi', navigate: true);

            return;
        }

        $otp = OtpCode::where('email', $registrasi['email'])
            ->where('kode_otp', $this->kode_otp)
            ->where('tipe', 'registrasi')
            ->whereNull('digunakan_pada')
            ->latest('id')
            ->first();

        if (! $otp || ! $otp->isValid()) {
            $this->addError('kode_otp', 'Kode OTP salah atau sudah kedaluwarsa.');

            return;
        }

        if (User::where('nik', $registrasi['nik'])->orWhere('email', $registrasi['email'])->exists()) {
            $this->addError('kode_otp', 'NIK atau Email ini sudah terdaftar sebagai akun pengguna.');

            return;
        }

        // Buat akun warga secara atomik — §11.1 poin 4.
        DB::transaction(function () use ($otp, $registrasi): void {
            $otp->update(['digunakan_pada' => now()]);

            $user = User::create([
                'name' => $registrasi['name'],
                'email' => $registrasi['email'],
                'password' => $registrasi['password'],
                'nik' => $registrasi['nik'],
                'no_hp' => $registrasi['no_hp'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $user->assignRole('warga');

            DataKependudukan::where('nik', $registrasi['nik'])
                ->update(['sudah_didaftarkan' => true]);
        });

        session()->forget('registrasi');
        session()->flash('status', 'Verifikasi berhasil! Akun Anda telah aktif. Silakan masuk.');

        $this->redirectRoute('portal.login', navigate: true);
    }

    public function kirimUlang(): void
    {
        $registrasi = session('registrasi');

        if (! $registrasi) {
            $this->redirectRoute('registrasi', navigate: true);

            return;
        }

        $kode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'email' => $registrasi['email'],
            'kode_otp' => $kode,
            'tipe' => 'registrasi',
            'kadaluarsa_pada' => now()->addMinutes(10),
        ]);

        Mail::to($registrasi['email'])->send(new KodeOtpMail($kode, $registrasi['name']));

        session()->flash('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    public function render()
    {
        return view('livewire.portal.otp-verifikasi');
    }
}
