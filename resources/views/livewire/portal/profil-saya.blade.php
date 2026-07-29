<div>
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900">Profil Saya &amp; Data Kependudukan</h1>
        <p class="mt-1 text-xs text-slate-500">Kelola informasi identitas SIAK kependudukan dan keamanan akun Anda.</p>
    </div>

    @if (session('status'))
        <div class="mt-4 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs font-bold text-emerald-800 flex items-center gap-2">
            <span>✓</span>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Digital SIAK Identity Card Mockup -->
        <div class="lg:col-span-1 rounded-3xl bg-white p-6 shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col justify-between">
            <div>
                <!-- Digital Card Header -->
                <div class="rounded-2xl bg-gradient-to-br from-slate-900 via-teal-950 to-emerald-900 p-5 text-white shadow-md relative overflow-hidden mb-6">
                    <div class="absolute -right-10 -bottom-10 h-32 w-32 rounded-full bg-emerald-500/10 blur-xl"></div>
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-3">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400">Identitas Digital Warga</span>
                        <span class="text-[10px] font-mono text-slate-400">DESA KARDULUK</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 text-slate-950 font-extrabold text-lg flex items-center justify-center shadow">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-white truncate max-w-[170px]">{{ $name }}</h2>
                            <p class="text-[11px] font-mono font-bold text-emerald-300 mt-0.5">NIK: {{ $nik ?: '-' }}</p>
                        </div>
                    </div>
                </div>

                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Data SIAK Kependudukan</h3>

                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="text-slate-400 block font-medium">Nama Lengkap:</span>
                        <span class="font-bold text-slate-800">{{ $penduduk?->nama ?? $name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Tempat, Tanggal Lahir:</span>
                        <span class="font-bold text-slate-800">
                            {{ $penduduk?->tempat_lahir ?? '-' }}, {{ $penduduk?->tanggal_lahir?->format('d F Y') ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Jenis Kelamin:</span>
                        <span class="font-bold text-slate-800">{{ $penduduk?->jenis_kelamin === 'L' ? 'Laki-laki' : ($penduduk?->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Alamat Domisili:</span>
                        <span class="font-bold text-slate-800 leading-relaxed">{{ $penduduk?->alamat ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-slate-400 block font-medium">RT / RW:</span>
                            <span class="font-bold text-slate-800">{{ $penduduk?->rt ?? '-' }} / {{ $penduduk?->rw ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Agama:</span>
                            <span class="font-bold text-slate-800">{{ $penduduk?->agama ?? 'Islam' }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Pekerjaan:</span>
                        <span class="font-bold text-slate-800">{{ $penduduk?->pekerjaan ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] text-slate-400 font-medium">
                *Data kependudukan terverifikasi dengan database SIAK Desa Karduluk.
            </div>
        </div>

        <!-- Forms Edit Contact & Security -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Edit Contact -->
            <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
                <h2 class="text-base font-extrabold text-slate-900 mb-4 border-b border-slate-100 pb-3">Informasi Kontak Akun</h2>

                <form wire:submit="simpanProfil" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Tampilan Profil</label>
                        <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all" />
                        @error('name') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" wire:model="email" class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all" />
                        @error('email') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp (Go-WA / Notifikasi)</label>
                        <input type="text" wire:model="no_hp" placeholder="08123456789" class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all" />
                        @error('no_hp') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 text-right">
                        <button type="submit" class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-3 text-xs font-bold text-white shadow-lg shadow-emerald-950/20 hover:from-emerald-400 hover:to-teal-400 transition-all">
                            Simpan Perubahan Kontak
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
                <h2 class="text-base font-extrabold text-slate-900 mb-4 border-b border-slate-100 pb-3">Keamanan &amp; Kata Sandi</h2>

                <form wire:submit="ubahPassword" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                        <input type="password" wire:model="password_lama" class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all" />
                        @error('password_lama') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" wire:model="password_baru" class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all" />
                            @error('password_baru') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" wire:model="password_konfirmasi" class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-semibold focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all" />
                            @error('password_konfirmasi') <span class="text-xs font-bold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-2 text-right">
                        <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-xs font-bold text-white shadow-md hover:bg-slate-800 transition-all">
                            Ubah Kata Sandi Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
