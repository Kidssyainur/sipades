<div>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Profil Warga</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola informasi identitas kependudukan dan keamanan akun Anda.</p>
    </div>

    @if (session('status'))
        <div class="mt-4 rounded-xl bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 border border-emerald-200">
            ✓ {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Informasi Kependudukan Resmi (Read Only) -->
        <div class="lg:col-span-1 rounded-2xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-4 pb-4 border-b border-gray-100 mb-4">
                    <div class="h-14 w-14 rounded-full bg-emerald-500 text-white font-bold text-xl flex items-center justify-center shadow-md">
                        {{ strtoupper(substr($name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">{{ $name }}</h2>
                        <span class="text-xs font-mono font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                            NIK: {{ $nik ?: '-' }}
                        </span>
                    </div>
                </div>

                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Data Kependudukan (SIAK)</h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-xs text-gray-400 block">Nama Lengkap:</span>
                        <span class="font-medium text-gray-800">{{ $penduduk?->nama ?? $name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Tempat, Tanggal Lahir:</span>
                        <span class="font-medium text-gray-800">
                            {{ $penduduk?->tempat_lahir ?? '-' }}, {{ $penduduk?->tanggal_lahir?->format('d F Y') ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Jenis Kelamin:</span>
                        <span class="font-medium text-gray-800">{{ $penduduk?->jenis_kelamin === 'L' ? 'Laki-laki' : ($penduduk?->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Alamat Tinggal:</span>
                        <span class="font-medium text-gray-800">{{ $penduduk?->alamat ?? '-' }}</span>
                    </div>
                    <div class="flex gap-4">
                        <div>
                            <span class="text-xs text-gray-400 block">RT / RW:</span>
                            <span class="font-medium text-gray-800">{{ $penduduk?->rt ?? '-' }} / {{ $penduduk?->rw ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Agama:</span>
                            <span class="font-medium text-gray-800">{{ $penduduk?->agama ?? 'Islam' }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Pekerjaan:</span>
                        <span class="font-medium text-gray-800">{{ $penduduk?->pekerjaan ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 text-[11px] text-gray-400">
                *Data kependudukan bersumber dari database Kependudukan Desa Karduluk.
            </div>
        </div>

        <!-- Form Edit Kontak & Ubah Password -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Edit Kontak -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                <h2 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3">Informasi Kontak Akun</h2>

                <form wire:submit="simpanProfil" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700">Nama Tampilan</label>
                        <input type="text" wire:model="name" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                        @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700">Alamat Email</label>
                        <input type="email" wire:model="email" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                        @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700">Nomor WhatsApp (Go-WA / Notifikasi)</label>
                        <input type="text" wire:model="no_hp" placeholder="08123456789" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                        @error('no_hp') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 text-right">
                        <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition">
                            Simpan Perubahan Kontak
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ubah Password -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                <h2 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3">Keamanan & Kata Sandi</h2>

                <form wire:submit="ubahPassword" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700">Kata Sandi Saat Ini</label>
                        <input type="password" wire:model="password_lama" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                        @error('password_lama') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700">Kata Sandi Baru</label>
                            <input type="password" wire:model="password_baru" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                            @error('password_baru') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" wire:model="password_konfirmasi" class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                            @error('password_konfirmasi') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-2 text-right">
                        <button type="submit" class="rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 transition">
                            Ubah Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
