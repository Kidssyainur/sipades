<?php

namespace App\Livewire\Portal;

use App\Models\DataKependudukan;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\WhatsappGatewayService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.portal')]
#[Title('Verifikasi OTP WhatsApp')]
class OtpVerifikasi extends Component
{
    public string $kode_otp = '';

    public ?string $targetNoHp = null;

    public ?string $targetNama = null;

    public function mount(): void
    {
        if (session()->has('login_warga')) {
            $data = session('login_warga');
            $this->targetNoHp = $data['no_hp'] ?? null;
            $this->targetNama = $data['name'] ?? null;
        } elseif (session()->has('registrasi')) {
            $data = session('registrasi');
            $this->targetNoHp = $data['no_hp'] ?? null;
            $this->targetNama = $data['name'] ?? null;
        } else {
            $this->redirectRoute('portal.login', navigate: true);
        }
    }

    public function verifikasi(WhatsappGatewayService $waService): void
    {
        $this->validate([
            'kode_otp' => ['required', 'digits:6'],
        ], [
            'kode_otp.digits' => 'Kode OTP terdiri dari 6 digit angka.',
        ]);

        // Verifikasi untuk Sesi Login Warga
        if (session()->has('login_warga')) {
            $loginData = session('login_warga');

            $otp = OtpCode::where('no_hp', $loginData['no_hp'])
                ->where('kode_otp', $this->kode_otp)
                ->where('tipe', 'login')
                ->whereNull('digunakan_pada')
                ->latest('id')
                ->first();

            if (! $otp || ! $otp->isValid()) {
                $this->addError('kode_otp', 'Kode OTP WhatsApp salah atau sudah kedaluwarsa.');

                return;
            }

            $otp->update(['digunakan_pada' => now()]);

            Auth::loginUsingId($loginData['user_id']);

            session()->forget('login_warga');
            session()->regenerate();
            session()->flash('status', 'Verifikasi WhatsApp berhasil! Selamat datang kembali.');

            $this->redirectRoute('portal.dashboard', navigate: true);

            return;
        }

        // Verifikasi untuk Sesi Registrasi Warga
        if (session()->has('registrasi')) {
            $registrasi = session('registrasi');

            $otp = OtpCode::where('no_hp', $registrasi['no_hp'])
                ->where('kode_otp', $this->kode_otp)
                ->where('tipe', 'registrasi')
                ->whereNull('digunakan_pada')
                ->latest('id')
                ->first();

            if (! $otp || ! $otp->isValid()) {
                $this->addError('kode_otp', 'Kode OTP WhatsApp salah atau sudah kedaluwarsa.');

                return;
            }

            if (User::where('nik', $registrasi['nik'])->orWhere('no_hp', $registrasi['no_hp'])->exists()) {
                $this->addError('kode_otp', 'NIK atau Nomor WhatsApp ini sudah terdaftar sebagai akun pengguna.');

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

                Auth::login($user);
            });

            session()->forget('registrasi');
            session()->regenerate();
            session()->flash('status', 'Registrasi & Verifikasi WhatsApp berhasil! Akun Anda aktif.');

            $this->redirectRoute('portal.dashboard', navigate: true);

            return;
        }

        $this->redirectRoute('portal.login', navigate: true);
    }

    public function kirimUlang(WhatsappGatewayService $waService): void
    {
        $noHp = null;
        $nama = null;
        $tipe = null;

        if (session()->has('login_warga')) {
            $data = session('login_warga');
            $noHp = $data['no_hp'];
            $nama = $data['name'];
            $tipe = 'login';
        } elseif (session()->has('registrasi')) {
            $data = session('registrasi');
            $noHp = $data['no_hp'];
            $nama = $data['name'];
            $tipe = 'registrasi';
        }

        if (! $noHp || ! $tipe) {
            $this->redirectRoute('portal.login', navigate: true);

            return;
        }

        $kode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'no_hp' => $noHp,
            'kode_otp' => $kode,
            'tipe' => $tipe,
            'kadaluarsa_pada' => now()->addMinutes(10),
        ]);

        $tujuanTeks = ($tipe === 'login') ? 'Masuk Portal Warga' : 'Registrasi Akun Warga';
        $waService->sendOtpMessage($noHp, $nama ?? 'Warga', $kode, $tujuanTeks);

        session()->flash('status', "Kode OTP baru telah dikirimkan ke nomor WhatsApp Anda ({$noHp}).");
    }

    public function render()
    {
        return view('livewire.portal.otp-verifikasi');
    }
}
