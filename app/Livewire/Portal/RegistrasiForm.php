<?php

namespace App\Livewire\Portal;

use App\Models\OtpCode;
use App\Models\User;
use App\Services\NikValidationService;
use App\Services\WhatsappGatewayService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.portal')]
#[Title('Registrasi Warga')]
class RegistrasiForm extends Component
{
    public string $nik = '';

    public string $name = '';

    public string $no_hp = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'nik' => ['required', 'digits:16', Rule::unique('users', 'nik')],
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20', Rule::unique('users', 'no_hp')],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    protected array $messages = [
        'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
        'nik.unique' => 'NIK ini sudah terdaftar sebagai akun pengguna. Silakan masuk.',
        'no_hp.unique' => 'Nomor WhatsApp ini sudah terdaftar. Silakan masuk.',
        'email.unique' => 'Email ini sudah terdaftar. Silakan login.',
        'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
    ];

    public function daftar(NikValidationService $nikValidation, WhatsappGatewayService $waService): void
    {
        $this->validate();

        $formattedNoHp = $waService->formatNoHp($this->no_hp);

        // FR-01: NIK harus ada di data kependudukan & belum pernah didaftarkan.
        $hasil = $nikValidation->validate($this->nik);

        if (! $hasil['valid']) {
            $this->addError('nik', $hasil['pesan']);

            return;
        }

        // Generate & simpan OTP registrasi (kedaluwarsa 10 menit) — §11.1.
        $kode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $userEmail = ! empty($this->email) ? $this->email : "{$this->nik}@karduluk.desa.id";

        OtpCode::create([
            'no_hp' => $formattedNoHp,
            'email' => $userEmail,
            'kode_otp' => $kode,
            'tipe' => 'registrasi',
            'kadaluarsa_pada' => now()->addMinutes(10),
        ]);

        // Simpan data calon akun sementara di session sampai OTP terverifikasi.
        session()->put('registrasi', [
            'nik' => $this->nik,
            'name' => $this->name,
            'no_hp' => $formattedNoHp,
            'email' => $userEmail,
            'password' => bcrypt($this->password),
        ]);

        // Kirim OTP via WhatsApp
        $waService->sendOtpMessage($formattedNoHp, $this->name, $kode, 'Registrasi Akun Warga');

        session()->flash('status', "Kode OTP registrasi telah dikirimkan ke nomor WhatsApp Anda ({$formattedNoHp}).");

        $this->redirectRoute('verifikasi-otp', navigate: true);
    }

    public function render()
    {
        return view('livewire.portal.registrasi-form');
    }
}
