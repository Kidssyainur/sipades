<div class="mx-auto max-w-md my-8">
    <div class="rounded-3xl bg-white p-8 sm:p-10 shadow-2xl shadow-slate-200/60 border border-slate-100">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white shadow-lg shadow-emerald-900/30 mb-3">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Verifikasi Kode OTP</h1>
            <p class="mt-1 text-xs text-slate-500 font-medium">Masukkan 6 digit kode keamanan OTP yang telah kami kirimkan.</p>
        </div>

        <form wire:submit="verifikasi" class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 text-center mb-2">Kode Otentikasi OTP</label>
                <input type="text" wire:model="kode_otp" inputmode="numeric" maxlength="6"
                    class="w-full rounded-2xl border-slate-200 bg-slate-50/50 p-3 text-center text-3xl font-extrabold font-mono tracking-[0.4em] focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all text-emerald-900"
                    placeholder="000000">
                @error('kode_otp') <p class="mt-2 text-xs font-bold text-rose-600 text-center">{{ $message }}</p> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:target="verifikasi"
                class="w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-3.5 text-xs font-extrabold text-white shadow-xl shadow-emerald-950/20 hover:from-emerald-400 hover:to-teal-400 transition-all disabled:opacity-60">
                <span wire:loading.remove wire:target="verifikasi">Verifikasi Sekarang →</span>
                <span wire:loading wire:target="verifikasi">Memverifikasi...</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs font-medium text-slate-500">
            Belum menerima kode OTP?
            <button type="button" wire:click="kirimUlang" wire:loading.attr="disabled" wire:target="kirimUlang"
                class="font-extrabold text-emerald-600 hover:text-emerald-800 ml-1">
                Kirim Ulang Kode OTP
            </button>
        </div>
    </div>
</div>
