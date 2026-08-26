<section id="potensi" class="relative scroll-mt-24 overflow-hidden bg-slate-950 py-20 sm:py-28">
    @php $potensi = config('landing.potensi'); @endphp
    <div class="pointer-events-none absolute -left-40 top-40 h-96 w-96 rounded-full bg-amber-500/5 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-end">
            <div>
                <span class="reveal inline-flex items-center gap-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400">
                    <span class="h-px w-8 bg-amber-400/60"></span> Potensi & UMKM
                </span>
                <h2 class="reveal mt-4 font-display text-3xl font-bold tracking-tight text-white sm:text-4xl reveal-delay-1">
                    Ekonomi dari <span class="italic text-amber-400">Tangan-tangan Terampil</span>
                </h2>
            </div>
            <p class="reveal max-w-md text-sm leading-relaxed text-slate-500 reveal-delay-2">
                Dari ukiran kayu bernilai ekspor, gula merah khas desa, hingga pertanian dan perikanan pesisir — ekonomi Karduluk berdenyut dari rumah-rumah warganya.
            </p>
        </div>

        <div class="mt-12 grid gap-5 lg:grid-cols-5">
            <!-- Kartu utama: Ukir -->
            <div class="reveal overflow-hidden rounded-2xl border border-white/10 bg-white/5 lg:col-span-3">
                <div class="grid sm:grid-cols-2">
                    <div class="p-8 sm:p-9">
                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-400/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest text-amber-400">
                            ✦ Ikon Utama Desa
                        </span>
                        <h3 class="mt-4 font-display text-xl font-bold leading-snug text-white">{{ $potensi['ukir']['judul'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-400">{{ $potensi['ukir']['deskripsi'] }}</p>
                        <p class="mt-4 rounded-xl border border-white/10 bg-slate-900/60 p-4 text-[13px] leading-relaxed text-slate-300">
                            <strong class="font-bold text-amber-400">Ciri motif:</strong> {{ $potensi['ukir']['ciri'] }}
                        </p>

                        <ul class="mt-6 space-y-3">
                            @foreach ($potensi['ukir']['angka'] as $angka)
                                <li class="flex items-center gap-4">
                                    <span class="font-display text-2xl font-bold text-amber-400">{{ $angka['nilai'] }}</span>
                                    <span class="text-[13px] font-medium leading-snug text-slate-300">{{ $angka['label'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="relative hidden min-h-full sm:block">
                        <img src="{{ asset('assets/seni_ukir.webp') }}"
                             alt="Produk ukiran kayu khas Madura"
                             class="absolute inset-0 h-full w-full object-cover"
                             loading="lazy"
                             onerror="if(!this.dataset.f){this.dataset.f=1;this.src='{{ asset('assets/seni_ukir.jpg') }}'}else{this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();}">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 rounded-xl bg-slate-950/70 p-3 text-xs font-semibold text-slate-300 backdrop-blur">
                            Produk: {{ implode(' · ', $potensi['ukir']['produk']) }}
                        </div>
                    </div>
                </div>
                <div class="border-t border-white/10 px-8 py-5 sm:px-9">
                    <p class="text-[13px] leading-relaxed text-slate-400">
                        <strong class="font-semibold text-emerald-400">Pemasaran:</strong> {{ $potensi['ukir']['pemasaran'] }}
                    </p>
                </div>
            </div>

            <!-- Kolom kanan -->
            <div class="flex flex-col gap-5 lg:col-span-2">
                <div class="reveal flex-1 rounded-2xl border border-amber-400/15 bg-gradient-to-br from-amber-500/10 to-transparent p-7 transition-all duration-300 hover:-translate-y-1 hover:border-amber-400/30 hover:bg-amber-500/10 reveal-delay-1">
                    <div class="relative mb-5 h-36 overflow-hidden rounded-xl">
                        <img src="{{ asset('assets/jhubata-gula-merah.webp') }}"
                             alt="Produk gula merah (Jhubata) khas Desa Karduluk"
                             class="h-full w-full object-cover"
                             loading="lazy"
                             onerror="if(!this.dataset.f){this.dataset.f=1;this.src='{{ asset('assets/jhubata-gula-merah.png') }}'}else{this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();}">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 to-transparent"></div>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 shadow-lg shadow-amber-950/40">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </span>
                    <h3 class="mt-4 font-display text-lg font-bold text-white">{{ $potensi['pangan']['judul'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-400">{{ $potensi['pangan']['deskripsi'] }}</p>
                    <p class="mt-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-slate-950/60 px-4 py-2 text-xs font-bold text-amber-300">
                        🍯 "Harum Manis" · Dusun Blajud
                    </p>
                </div>

                <div class="reveal flex-1 rounded-2xl border border-emerald-400/15 bg-gradient-to-br from-emerald-500/10 to-transparent p-7 transition-all duration-300 hover:-translate-y-1 hover:border-emerald-400/30 hover:bg-emerald-500/10 reveal-delay-2">
                    <div class="relative mb-5 h-36 overflow-hidden rounded-xl">
                        <img src="{{ asset('assets/pertanian_karduluk.webp') }}"
                             alt="Lahan pertanian dan perikanan Desa Karduluk"
                             class="h-full w-full object-cover"
                             loading="lazy"
                             onerror="if(!this.dataset.f){this.dataset.f=1;this.src='{{ asset('assets/pertanian_karduluk.jpg') }}'}else{this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();}">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 to-transparent"></div>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-950/40">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                    </span>
                    <h3 class="mt-4 font-display text-lg font-bold text-white">{{ $potensi['sektor']['judul'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-400">{{ $potensi['sektor']['deskripsi'] }}</p>
                    <div class="mt-4 flex gap-2">
                        <span class="rounded-full border border-white/10 bg-slate-950/60 px-4 py-2 text-xs font-bold text-emerald-300">🌾 Sawah & Ladang</span>
                        <span class="rounded-full border border-white/10 bg-slate-950/60 px-4 py-2 text-xs font-bold text-emerald-300">⛵ Nelayan Pesisir</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
