<?php

namespace App\Livewire\Portal;

use App\Mail\KodeOtpMail;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\NikValidationService;
use Illuminate\Support\Facades\Mail;
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
            'no_hp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    protected array $messages = [
        'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
        'nik.unique' => 'NIK ini sudah terdaftar sebagai akun pengguna. Silakan login.',
        'email.unique' => 'Email ini sudah terdaftar. Silakan login.',
        'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
    ];

    public function daftar(NikValidationService $nikValidation): void
    {
        $this->validate();

        // FR-01: NIK harus ada di data kependudukan & belum pernah didaftarkan.
        $hasil = $nikValidation->validate($this->nik);

        if (! $hasil['valid']) {
            $this->addError('nik', $hasil['pesan']);

            return;
        }

        // Generate & simpan OTP registrasi (kedaluwarsa 10 menit) — §11.1.
        $kode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'email' => $this->email,
            'kode_otp' => $kode,
            'tipe' => 'registrasi',
            'kadaluarsa_pada' => now()->addMinutes(10),
        ]);

        // Simpan data calon akun sementara di session sampai OTP terverifikasi.
        session()->put('registrasi', [
            'nik' => $this->nik,
            'name' => $this->name,
            'no_hp' => $this->no_hp,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        Mail::to($this->email)->send(new KodeOtpMail($kode, $this->name));

        session()->flash('status', 'Kode OTP telah dikirim ke email Anda. Silakan cek kotak masuk.');

        $this->redirectRoute('verifikasi-otp', navigate: true);
    }

    public function render()
    {
        return view('livewire.portal.registrasi-form');
    }
}
