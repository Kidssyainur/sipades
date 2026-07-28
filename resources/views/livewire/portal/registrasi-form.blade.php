<div class="mx-auto max-w-md">
    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
        <h1 class="text-2xl font-bold text-gray-900">Registrasi Akun Warga</h1>
        <p class="mt-1 text-sm text-gray-500">
            Masukkan data sesuai dengan data kependudukan desa. NIK akan diverifikasi otomatis.
        </p>

        <form wire:submit="daftar" class="mt-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">NIK</label>
                <input type="text" wire:model="nik" inputmode="numeric" maxlength="16"
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    placeholder="16 digit NIK">
                @error('nik') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" wire:model="name"
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                <input type="text" wire:model="no_hp"
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    placeholder="08xxxxxxxxxx">
                @error('no_hp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email"
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                <input type="password" wire:model="password"
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
                <input type="password" wire:model="password_confirmation"
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <button type="submit" wire:loading.attr="disabled"
                class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 font-semibold text-white hover:bg-emerald-500 disabled:opacity-60">
                <span wire:loading.remove wire:target="daftar">Daftar & Kirim OTP</span>
                <span wire:loading wire:target="daftar">Memproses…</span>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Sudah punya akun?
            <a href="{{ route('portal.login') }}" wire:navigate class="font-medium text-emerald-600 hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>
