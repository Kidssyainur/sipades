<?php

namespace App\Livewire\Portal;

use App\Models\DataKependudukan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.portal')]
#[Title('Profil Saya')]
class ProfilSaya extends Component
{
    public string $name = '';

    public string $email = '';

    public string $no_hp = '';

    public string $nik = '';

    public ?DataKependudukan $penduduk = null;

    public string $password_lama = '';

    public string $password_baru = '';

    public string $password_konfirmasi = '';

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->no_hp = $user->no_hp ?? '';
        $this->nik = $user->nik ?? '';

        if (! empty($user->nik)) {
            $this->penduduk = DataKependudukan::where('nik', $user->nik)->first();
        }
    }

    public function simpanProfil(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'no_hp' => ['required', 'string', 'max:20'],
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
        ]);

        session()->flash('status', 'Profil berhasil diperbarui.');
    }

    public function ubahPassword(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'min:6'],
            'password_konfirmasi' => ['required', 'same:password_baru'],
        ]);

        if (! Hash::check($this->password_lama, $user->password)) {
            $this->addError('password_lama', 'Kata sandi lama tidak sesuai.');

            return;
        }

        $user->update([
            'password' => Hash::make($this->password_baru),
        ]);

        $this->reset(['password_lama', 'password_baru', 'password_konfirmasi']);

        session()->flash('status', 'Kata sandi berhasil diubah.');
    }

    public function render()
    {
        return view('livewire.portal.profil-saya');
    }
}
