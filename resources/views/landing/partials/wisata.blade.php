<section id="wisata" class="relative scroll-mt-24 overflow-hidden bg-slate-900 py-20 sm:py-28">
    <div class="pointer-events-none absolute inset-0 opacity-[0.04] pattern-ornament"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <span class="reveal inline-flex items-center gap-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400">
                <span class="h-px w-8 bg-amber-400/60"></span> Pariwisata & Cagar Budaya <span class="h-px w-8 bg-amber-400/60"></span>
            </span>
            <h2 class="reveal mt-4 font-display text-3xl font-bold tracking-tight text-white sm:text-4xl reveal-delay-1">
                Pesona Pesisir <span class="italic text-amber-400">Selatan Madura</span>
            </h2>
            <p class="reveal mt-4 text-slate-400 reveal-delay-2">
                Dari tebing batu yang menyuguhkan laut lepas hingga jejak kolonial yang membisu di tepi pantai — semua berjarak sekitar 2 km dari Balai Desa.
            </p>
        </div>

        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $gambarWisata = [
                    'gunung' => asset('assets/batu_sulung.webp'),
                    'jembatan' => ['src' => asset('assets/viaduk.webp'), 'fb' => asset('assets/viaduk.png')],
                    'batu' => ['src' => asset('assets/tosolong.webp'), 'fb' => asset('assets/tosolong.jpg')],
                    'ukir' => asset('assets/wisata_ukir.jpg'),
                ];
            @endphp
            @foreach (config('landing.wisata') as $index => $w)
                @php $g = $gambarWisata[$w['ikon']]; @endphp
                <article class="reveal group relative overflow-hidden rounded-2xl border border-white/10 shadow-lg shadow-black/40 transition-all duration-500 hover:-translate-y-2 hover:border-amber-400/30 hover:shadow-2xl"
                         style="--reveal-delay: {{ $index * 100 }}ms">
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ is_array($g) ? $g['src'] : $g }}"
                             alt="{{ $w['nama'] }}"
                             class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                             loading="lazy"
                             @if (is_array($g))
                             onerror="if(!this.dataset.f){this.dataset.f=1;this.src='{{ $g['fb'] }}'}else{this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();}"
                             @else
                             onerror="this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();"
                             @endif
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/25 to-transparent"></div>
                        <span class="absolute left-3.5 top-3.5 flex items-center gap-1.5 rounded-full bg-slate-950/70 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest text-amber-300 backdrop-blur">
                            📍 {{ $w['lokasi'] }}
                        </span>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 p-4">
                        <h3 class="font-display text-lg font-bold leading-snug text-white">{{ $w['nama'] }}</h3>
                    </div>

                    <!-- Deskripsi hover -->
                    <div class="absolute inset-0 flex translate-y-full flex-col justify-end bg-gradient-to-t from-slate-950/95 via-slate-950/85 to-slate-950/40 p-5 transition-transform duration-500 group-hover:translate-y-0">
                        <h3 class="font-display text-lg font-bold text-white">{{ $w['nama'] }}</h3>
                        <p class="mt-2 text-xs leading-relaxed text-slate-300">{{ $w['deskripsi'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        <p class="reveal mx-auto mt-10 flex max-w-2xl items-start gap-3 rounded-2xl border border-amber-400/15 bg-amber-400/5 px-5 py-4 text-sm leading-relaxed text-amber-200/90">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ config('landing.wisata_akses') }}
        </p>
    </div>
</section>
