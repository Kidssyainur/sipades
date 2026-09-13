<div class="mx-auto max-w-lg my-6">
    <div class="rounded-3xl bg-white p-8 sm:p-10 shadow-2xl shadow-slate-200/60 border border-slate-100">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-16 w-16 overflow-hidden rounded-full bg-white shadow-lg shadow-emerald-900/20 ring-2 ring-emerald-400/50 mb-3">
                <img src="{{ asset('assets/logo-karduluk.webp') }}"
                     alt="Logo Desa Karduluk"
                     class="h-full w-full object-cover"
                     onerror="this.onerror=null;this.src='{{ asset('assets/logo-karduluk.png') }}';">
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Registrasi Akun Warga</h1>
            <p class="mt-1 text-xs text-slate-500 font-medium">Lengkapi data NIK kependudukan Anda untuk pendaftaran akun.</p>
        </div>

        <form wire:submit="daftar" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                <input type="text" wire:model="nik" inputmode="numeric" maxlength="16" placeholder="16 Digit NIK Sesuai KTP"
                    class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all font-mono">
                @error('nik') <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Sesuai KTP</label>
                <input type="text" wire:model="name" placeholder="Nama Lengkap"
                    class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all">
                @error('name') <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp Aktif</label>
                <input type="tel" wire:model="no_hp" placeholder="08123456789 / 628123456789" inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                    class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all">
                @error('no_hp') <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                <input type="email" wire:model="email" placeholder="nama@domain.com"
                    class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all">
                @error('email') <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi</label>
                    <input type="password" wire:model="password" placeholder="••••••••"
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all">
                    @error('password') <p class="mt-1 text-xs font-bold text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi</label>
                    <input type="password" wire:model="password_confirmation" placeholder="••••••••"
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all">
                </div>
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:target="daftar"
                class="w-full mt-2 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-3.5 text-xs font-extrabold text-white shadow-xl shadow-emerald-950/20 hover:from-emerald-400 hover:to-teal-400 transition-all disabled:opacity-60">
                <span wire:loading.remove wire:target="daftar">Daftar Akun &amp; Kirim OTP →</span>
                <span wire:loading wire:target="daftar">Memproses Pendaftaran...</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs font-medium text-slate-500">
            Sudah memiliki akun warga?
            <a href="{{ route('portal.login') }}" wire:navigate class="font-extrabold text-emerald-600 hover:text-emerald-800">Masuk di sini</a>
        </div>
    </div>
</div>
