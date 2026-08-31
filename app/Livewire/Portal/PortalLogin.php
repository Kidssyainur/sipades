<?php

namespace App\Livewire\Portal;

use App\Models\OtpCode;
use App\Models\User;
use App\Services\WhatsappGatewayService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.portal')]
#[Title('Masuk Portal Warga')]
class PortalLogin extends Component
{
    public string $nik = '';

    public string $no_hp = '';

    public function login(WhatsappGatewayService $waService)
    {
        $this->validate([
            'nik' => ['required', 'digits:16'],
            'no_hp' => ['required', 'string'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'no_hp.required' => 'Nomor WhatsApp terdaftar wajib diisi.',
        ]);

        $cleanInputNo = preg_replace('/[^0-9]/', '', $this->no_hp);
        $formattedNoHp = $waService->formatNoHp($cleanInputNo);
        $localNoHp = str_starts_with($formattedNoHp, '62') ? ('0' . substr($formattedNoHp, 2)) : $formattedNoHp;

        $targetNumbers = array_unique(array_filter([
            $formattedNoHp,
            $localNoHp,
            $cleanInputNo,
            $this->no_hp,
        ]));

        /** @var User|null $user */
        $user = User::role('warga')
            ->where('nik', trim($this->nik))
            ->where(function ($q) use ($targetNumbers) {
                $q->whereIn('no_hp', $targetNumbers);
            })
            ->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'nik' => 'Kombinasi NIK dan Nomor WhatsApp tidak ditemukan pada sistem.',
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'nik' => 'Akun Anda tidak aktif. Hubungi petugas desa.',
            ]);
        }

        // Generate & simpan OTP login (kedaluwarsa 10 menit)
        $kode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'no_hp' => $formattedNoHp,
            'email' => $user->email,
            'kode_otp' => $kode,
            'tipe' => 'login',
            'kadaluarsa_pada' => now()->addMinutes(10),
        ]);

        session()->put('login_warga', [
            'user_id' => $user->id,
            'nik' => $user->nik,
            'no_hp' => $formattedNoHp,
            'name' => $user->name,
        ]);

        // Kirim OTP via WhatsApp menggunakan template resmi
        $waService->sendOtpMessage($formattedNoHp, $user->name, $kode, 'Masuk Portal Warga');

        session()->flash('status', "Kode OTP untuk masuk telah dikirimkan ke nomor WhatsApp Anda ({$formattedNoHp}).");

        return $this->redirectRoute('verifikasi-otp', navigate: true);
    }

    public function render()
    {
        return view('livewire.portal.portal-login');
    }
}
