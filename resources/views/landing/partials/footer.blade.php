<footer class="border-t border-white/10 bg-slate-950 text-slate-400">
    <!-- CTA strip -->
    <div class="border-b border-white/10 bg-gradient-to-r from-slate-900 via-slate-900 to-emerald-950/60">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-4 py-10 sm:px-6 lg:flex-row lg:px-8">
            <div class="text-center lg:text-left">
                <h3 class="font-display text-2xl font-bold text-white">Siap mengurus surat secara online?</h3>
                <p class="mt-1 text-sm text-slate-400">Daftar akun warga dan ajukan permohonan surat dari rumah — hemat waktu, tanpa antre.</p>
            </div>
            <div class="flex shrink-0 gap-3">
                <a href="{{ route('registrasi') }}" class="rounded-full bg-gradient-to-r from-amber-400 to-orange-500 px-6 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-amber-950/30 transition-all duration-300 hover:brightness-110 active:scale-95">
                    Daftar Sekarang
                </a>
                <a href="{{ route('portal.login') }}" class="rounded-full border border-white/20 px-6 py-3 text-sm font-bold text-slate-200 transition hover:bg-white/10 hover:text-white">
                    Masuk
                </a>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <!-- Brand -->
            <div>
                <a href="#beranda" class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-full bg-white ring-2 ring-amber-400/40 shadow-lg shadow-amber-950/30">
                        <img src="{{ asset('assets/logo-karduluk.webp') }}"
                             alt="Logo Desa Karduluk"
                             class="h-full w-full object-cover"
                             onerror="this.onerror=null;this.src='{{ asset('assets/logo-karduluk.png') }}';">
                    </span>
                    <span>
                        <span class="block font-display text-lg font-bold text-white">Desa Karduluk</span>
                        <span class="block text-[10px] font-semibold uppercase tracking-widest text-amber-400">Sentra Ukir Madura</span>
                    </span>
                </a>
                <p class="mt-4 text-[13px] leading-relaxed">
                    Pemerintah Desa Karduluk, Kecamatan Pragaan, Kabupaten Sumenep, Jawa Timur — melayani warga dengan pelayanan administrasi surat-menyurat yang cepat, transparan, dan dapat diakses secara digital.
                </p>
            </div>

            <!-- Navigasi -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-white">Jelajahi</h4>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @foreach ([
                        'profil' => 'Profil Desa', 'pemerintahan' => 'Pemerintahan', 'potensi' => 'Potensi & UMKM',
                        'wisata' => 'Wisata', 'berita' => 'Berita & Kegiatan', 'layanan' => 'Layanan', 'kontak' => 'Kontak',
                    ] as $anchor => $label)
                        <li><a href="#{{ $anchor }}" class="transition hover:text-amber-300">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Layanan digital -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-white">Layanan Digital</h4>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('registrasi') }}" class="transition hover:text-amber-300">Registrasi Akun Warga</a></li>
                    <li><a href="{{ route('portal.login') }}" class="transition hover:text-amber-300">Masuk Portal Warga</a></li>
                    <li><a href="#layanan" class="transition hover:text-amber-300">Alur Pengurusan Surat</a></li>
                    <li><a href="#layanan" class="transition hover:text-amber-300">Pengaduan & Aspirasi</a></li>
                    <li><a href="#layanan" class="transition hover:text-amber-300">Musrenbangdes</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-white">Kontak</h4>
                <ul class="mt-4 space-y-3 text-sm">
                    <li class="flex gap-2.5"><span class="text-amber-400">📍</span> {{ config('landing.kontak.alamat') }}</li>
                    <li class="flex gap-2.5"><span class="text-amber-400">📞</span>
                        <a href="https://wa.me/6285257379290" target="_blank" rel="noopener" class="transition hover:text-amber-300">{{ config('landing.kontak.telepon') }}</a>
                    </li>
                    <li class="flex gap-2.5"><span class="text-amber-400">✉️</span>
                        <a href="mailto:{{ config('landing.kontak.email') }}" class="transition hover:text-amber-300">{{ config('landing.kontak.email') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-6 text-xs sm:flex-row sm:px-6 lg:px-8">
            <p>© {{ now()->year }} Pemerintah Desa {{ config('desa.nama') }}. Hak cipta dilindungi.</p>
            <p class="flex items-center gap-1.5">
                Ditenagai oleh
                <a href="#beranda" class="font-bold text-emerald-400 transition hover:text-amber-300">SIPADES</a>
                — Sistem Informasi Pelayanan Desa
            </p>
        </div>
    </div>
</footer>
