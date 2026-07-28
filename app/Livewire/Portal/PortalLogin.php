<?php

namespace App\Livewire\Portal;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.portal')]
#[Title('Masuk')]
class PortalLogin extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi salah.',
            ]);
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda tidak aktif. Hubungi petugas desa.',
            ]);
        }

        session()->regenerate();

        // FR-02: redirect berbasis role. Warga → portal, staf → panel Filament.
        if ($user->isWarga()) {
            return $this->redirectRoute('portal.dashboard', navigate: true);
        }

        return $this->redirect('/admin', navigate: false);
    }

    public function render()
    {
        return view('livewire.portal.portal-login');
    }
}
