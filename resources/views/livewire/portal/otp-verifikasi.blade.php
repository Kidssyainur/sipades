<div class="mx-auto max-w-md my-8">
    <div class="rounded-3xl bg-white p-8 sm:p-10 shadow-2xl shadow-slate-200/60 border border-slate-100">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-16 w-16 overflow-hidden rounded-full bg-white shadow-lg shadow-emerald-900/20 ring-2 ring-emerald-400/50 mb-3">
                <img src="{{ asset('assets/logo-karduluk.webp') }}"
                     alt="Logo Desa Karduluk"
                     class="h-full w-full object-cover"
                     onerror="this.onerror=null;this.src='{{ asset('assets/logo-karduluk.png') }}';">
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Verifikasi Kode OTP WhatsApp</h1>
            <p class="mt-1 text-xs text-slate-500 font-medium">
                Masukkan 6 digit kode OTP yang kami kirimkan ke WhatsApp
                @if($targetNoHp)
                    <strong class="text-emerald-700 font-bold block mt-1 font-mono text-sm bg-emerald-50 py-1 px-2 rounded border border-emerald-200 inline-block">{{ $targetNoHp }}</strong>
                @endif
            </p>
        </div>

        @if (session('status'))
            <div class="mb-5 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs font-semibold text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

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
            Belum menerima kode OTP WhatsApp?
            <button type="button" wire:click="kirimUlang" wire:loading.attr="disabled" wire:target="kirimUlang"
                class="font-extrabold text-emerald-600 hover:text-emerald-800 ml-1">
                Kirim Ulang ke WhatsApp
            </button>
        </div>
    </div>
</div>
