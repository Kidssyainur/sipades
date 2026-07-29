<div class="mx-auto max-w-md my-8">
    <div class="rounded-3xl bg-white p-8 sm:p-10 shadow-2xl shadow-slate-200/60 border border-slate-100">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white shadow-lg shadow-emerald-900/30 mb-3">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6" />
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Masuk Portal Warga</h1>
            <p class="mt-1 text-xs text-slate-500 font-medium">Gunakan email dan kata sandi akun warga Anda.</p>
        </div>

        <form wire:submit="login" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </span>
                    <input type="email" wire:model="email" placeholder="warga@karduluk.desa.id"
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 pl-10 pr-4 py-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all">
                </div>
                @error('email') <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <input type="password" wire:model="password" placeholder="••••••••"
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 pl-10 pr-4 py-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all">
                </div>
                @error('password') <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer">
                    <input type="checkbox" wire:model="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    Ingat saya
                </label>
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:target="login"
                class="w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-3.5 text-xs font-extrabold text-white shadow-xl shadow-emerald-950/20 hover:from-emerald-400 hover:to-teal-400 transition-all disabled:opacity-60">
                <span wire:loading.remove wire:target="login">Masuk ke Portal →</span>
                <span wire:loading wire:target="login">Memproses Masuk...</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs font-medium text-slate-500">
            Belum memiliki akun warga?
            <a href="{{ route('registrasi') }}" wire:navigate class="font-extrabold text-emerald-600 hover:text-emerald-800">Daftar Akun Baru</a>
        </div>
    </div>
</div>
