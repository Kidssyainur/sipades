<div class="mx-auto max-w-md my-8">
    <div class="rounded-3xl bg-white p-8 sm:p-10 shadow-2xl shadow-slate-200/60 border border-slate-100">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white shadow-lg shadow-emerald-900/30 mb-3">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Masuk Portal Warga</h1>
            <p class="mt-1 text-xs text-slate-500 font-medium">Gunakan NIK dan Nomor WhatsApp yang telah terdaftar.</p>
        </div>

        @if (session('status'))
            <div class="mb-5 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs font-semibold text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="login" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                    </span>
                    <input type="text" wire:model="nik" inputmode="numeric" maxlength="16" placeholder="16 Digit NIK Sesuai KTP"
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 pl-10 pr-4 py-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all font-mono">
                </div>
                @error('nik') <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp Terdaftar</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </span>
                    <input type="text" wire:model="no_hp" placeholder="08xxx / 628xxx"
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 pl-10 pr-4 py-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all">
                </div>
                @error('no_hp') <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:target="login"
                class="w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-3.5 text-xs font-extrabold text-white shadow-xl shadow-emerald-950/20 hover:from-emerald-400 hover:to-teal-400 transition-all disabled:opacity-60">
                <span wire:loading.remove wire:target="login">Kirim OTP WhatsApp &amp; Masuk →</span>
                <span wire:loading wire:target="login">Memproses Kode OTP...</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs font-medium text-slate-500">
            Belum memiliki akun warga?
            <a href="{{ route('registrasi') }}" wire:navigate class="font-extrabold text-emerald-600 hover:text-emerald-800">Daftar Akun Baru</a>
        </div>
    </div>
</div>
