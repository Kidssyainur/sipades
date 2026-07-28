<div class="mx-auto max-w-md">
    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
        <h1 class="text-2xl font-bold text-gray-900">Verifikasi OTP</h1>
        <p class="mt-1 text-sm text-gray-500">
            Masukkan 6 digit kode yang telah kami kirim ke email Anda.
        </p>

        <form wire:submit="verifikasi" class="mt-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Kode OTP</label>
                <input type="text" wire:model="kode_otp" inputmode="numeric" maxlength="6"
                    class="mt-1 w-full rounded-lg border-gray-300 text-center text-2xl tracking-[0.5em] shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    placeholder="000000">
                @error('kode_otp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:target="verifikasi"
                class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 font-semibold text-white hover:bg-emerald-500 disabled:opacity-60">
                <span wire:loading.remove wire:target="verifikasi">Verifikasi</span>
                <span wire:loading wire:target="verifikasi">Memverifikasi…</span>
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            Tidak menerima kode?
            <button type="button" wire:click="kirimUlang" wire:loading.attr="disabled" wire:target="kirimUlang"
                class="font-medium text-emerald-600 hover:underline">
                Kirim ulang OTP
            </button>
        </div>
    </div>
</div>
