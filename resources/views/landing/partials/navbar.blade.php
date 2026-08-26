@php
    $navMenu = [
        ['label' => 'Beranda', 'anchor' => 'beranda', 'children' => []],
        ['label' => 'Profil', 'anchor' => null, 'children' => [
            ['label' => 'Profil Desa', 'anchor' => 'profil', 'desc' => 'Sejarah & karakter wilayah'],
            ['label' => 'Pemerintahan', 'anchor' => 'pemerintahan', 'desc' => 'Kades, struktur & anggaran'],
            ['label' => 'Fasilitas & Pendidikan', 'anchor' => 'fasilitas', 'desc' => 'Sekolah, pesantren & kesehatan'],
        ]],
        ['label' => 'Potensi', 'anchor' => null, 'children' => [
            ['label' => 'UMKM & Produk Unggulan', 'anchor' => 'potensi', 'desc' => 'Ukir kayu, gula merah & pangan'],
            ['label' => 'Wisata & Cagar Budaya', 'anchor' => 'wisata', 'desc' => 'Batu Sulung, viaduk & Tosolong'],
        ]],
        ['label' => 'Berita', 'anchor' => 'berita', 'children' => []],
        ['label' => 'Layanan', 'anchor' => null, 'align' => 'right', 'children' => [
            ['label' => 'Alur Pelayanan Desa', 'anchor' => 'layanan', 'desc' => 'Surat-menyurat & pengaduan'],
            ['label' => 'Ajukan Surat Online', 'anchor' => null, 'route' => 'registrasi', 'desc' => 'SIPADES — tanpa antre'],
            ['label' => 'Masuk Portal Warga', 'anchor' => null, 'route' => 'portal.login', 'desc' => 'Lacak status pengajuan'],
        ]],
        ['label' => 'Kontak', 'anchor' => 'kontak', 'children' => []],
    ];
@endphp

<header id="landing-navbar" class="navbar-transparent fixed inset-x-0 top-0 z-50 transition-all duration-300">
    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
        <!-- Brand -->
        <a href="#beranda" class="group flex shrink-0 items-center gap-2.5">
            <span class="relative flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-white shadow-lg shadow-amber-950/30 ring-2 ring-amber-400/40 transition-transform duration-300 group-hover:scale-105">
                <img src="{{ asset('assets/logo-karduluk.webp') }}"
                     alt="Logo Desa Karduluk"
                     class="h-full w-full object-cover"
                     onerror="this.onerror=null;this.src='{{ asset('assets/logo-karduluk.png') }}';">
            </span>
            <span class="leading-tight">
                <span class="block text-[15px] font-extrabold tracking-tight text-white">Desa Karduluk</span>
                <span class="block text-[10px] font-semibold uppercase tracking-[0.18em] text-amber-400">Sentra Ukir Madura</span>
            </span>
        </a>

        <!-- Desktop menu -->
        <div class="hidden items-center gap-0.5 lg:flex">
            @foreach ($navMenu as $item)
                @if (empty($item['children']))
                    <a href="#{{ $item['anchor'] }}"
                       class="nav-link rounded-full px-3.5 py-2 text-sm font-semibold text-slate-300 transition-colors duration-200 hover:bg-white/10 hover:text-white">
                        {{ $item['label'] }}
                    </a>
                @else
                    <div class="nav-dropdown relative">
                        <button type="button"
                                class="nav-dropdown-btn flex items-center gap-1.5 rounded-full px-3.5 py-2 text-sm font-semibold text-slate-300 transition-colors duration-200 hover:bg-white/10 hover:text-white"
                                aria-expanded="false">
                            {{ $item['label'] }}
                            <svg class="nav-chevron h-3.5 w-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div class="nav-dropdown-panel invisible absolute top-full z-50 w-72 translate-y-2 pt-3 opacity-0 transition-all duration-200 {{ $item['align'] ?? null === 'right' ? 'right-0 translate-x-0' : 'left-1/2 -translate-x-1/2' }}">
                            <div class="overflow-hidden rounded-2xl border border-white/10 bg-slate-900/95 p-2 shadow-2xl shadow-black/60 backdrop-blur-xl">
                                @foreach ($item['children'] as $child)
                                    @php
                                        $href = $child['route'] ?? null
                                            ? route($child['route'])
                                            : '#' . $child['anchor'];
                                        $external = !empty($child['route']);
                                    @endphp
                                    <a href="{{ $href }}"
                                       class="group flex items-start gap-3 rounded-xl px-3.5 py-3 transition-colors duration-150 hover:bg-white/10">
                                        <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-400/10 text-amber-400 transition-colors group-hover:bg-amber-400/20">
                                            @if ($external)
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                                            @else
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                            @endif
                                        </span>
                                        <span>
                                            <span class="block text-sm font-bold text-white">{{ $child['label'] }}</span>
                                            <span class="mt-0.5 block text-[11px] leading-snug text-slate-400">{{ $child['desc'] }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Actions -->
        <div class="flex shrink-0 items-center gap-2">
            <a href="{{ route('portal.login') }}"
               class="hidden rounded-full border border-white/15 px-4 py-2 text-sm font-bold text-slate-200 transition-all duration-200 hover:border-amber-400/50 hover:text-white sm:inline-flex">
                Masuk
            </a>
            <a href="{{ route('registrasi') }}"
               class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 px-4 py-2 text-sm font-bold text-slate-950 shadow-lg shadow-amber-950/30 transition-all duration-300 hover:shadow-amber-900/40 hover:brightness-110 active:scale-95">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Ajukan Surat
            </a>

            <button id="mobile-menu-btn" type="button" aria-label="Buka menu"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/15 bg-white/5 text-white transition hover:bg-white/10 lg:hidden">
                <svg id="icon-open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                <svg id="icon-close" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
    </nav>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="mx-4 hidden overflow-hidden rounded-2xl border border-white/10 bg-slate-950/95 shadow-2xl backdrop-blur-xl lg:hidden">
        <div class="max-h-[75vh] overflow-y-auto p-3">
            @foreach ($navMenu as $item)
                @if (empty($item['children']))
                    <a href="#{{ $item['anchor'] }}"
                       class="mobile-nav-link flex items-center justify-between rounded-xl px-4 py-3 text-sm font-bold text-white/85 transition hover:bg-white/10 hover:text-white">
                        {{ $item['label'] }}
                    </a>
                @else
                    <div class="mobile-accordion">
                        <button type="button" class="mobile-accordion-btn flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-bold text-white/85 transition hover:bg-white/10 hover:text-white" aria-expanded="false">
                            {{ $item['label'] }}
                            <svg class="mobile-chevron h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="mobile-accordion-panel hidden">
                            <div class="ml-3 space-y-1 border-l border-white/10 pl-3">
                                @foreach ($item['children'] as $child)
                                    @php
                                        $href = $child['route'] ?? null
                                            ? route($child['route'])
                                            : '#' . $child['anchor'];
                                    @endphp
                                    <a href="{{ $href }}"
                                       class="mobile-nav-link block rounded-lg px-3.5 py-2.5 text-[13px] font-semibold text-slate-400 transition hover:bg-white/10 hover:text-white">
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="mt-2 flex gap-2 border-t border-white/10 pt-3">
                <a href="{{ route('portal.login') }}" class="flex-1 rounded-xl border border-white/15 px-4 py-2.5 text-center text-sm font-bold text-white/85 transition hover:bg-white/10">Masuk</a>
                <a href="{{ route('registrasi') }}" class="flex-1 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 px-4 py-2.5 text-center text-sm font-bold text-slate-950">Daftar</a>
            </div>
        </div>
    </div>
</header>
