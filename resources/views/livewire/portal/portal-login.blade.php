<div class="mx-auto max-w-md">
    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
        <h1 class="text-2xl font-bold text-gray-900">Masuk ke Portal</h1>
        <p class="mt-1 text-sm text-gray-500">Gunakan email dan kata sandi akun Anda.</p>

        <form wire:submit="login" class="mt-6 space-y-4">
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

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" wire:model="remember" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                Ingat saya
            </label>

            <button type="submit" wire:loading.attr="disabled" wire:target="login"
                class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 font-semibold text-white hover:bg-emerald-500 disabled:opacity-60">
                <span wire:loading.remove wire:target="login">Masuk</span>
                <span wire:loading wire:target="login">Memproses…</span>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Belum punya akun?
            <a href="{{ route('registrasi') }}" wire:navigate class="font-medium text-emerald-600 hover:underline">Daftar di sini</a>
        </p>
    </div>
</div>
