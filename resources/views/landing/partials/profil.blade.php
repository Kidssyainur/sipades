<section id="profil" class="relative scroll-mt-24 overflow-hidden bg-slate-950 py-20 sm:py-28">
    <div class="pointer-events-none absolute -right-40 top-20 h-96 w-96 rounded-full bg-amber-500/5 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <!-- Visual kiri -->
            <div class="reveal relative order-2 lg:order-1">
                <div class="absolute -left-4 -top-4 h-32 w-32 rounded-3xl border border-dashed border-amber-400/20"></div>
                <div class="relative aspect-[4/3] w-full overflow-hidden rounded-[1.75rem] border border-white/10 shadow-2xl shadow-black/50">
                    <img src="{{ asset('assets/pertanian_karduluk.webp') }}"
                         alt="Lahan pertanian dan alam Desa Karduluk"
                         class="absolute inset-0 h-full w-full object-cover"
                         loading="lazy"
                         onerror="if(!this.dataset.f){this.dataset.f=1;this.src='{{ asset('assets/pertanian_karduluk.jpg') }}'}else{this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();}">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 rounded-xl border border-white/10 bg-slate-950/70 p-4 backdrop-blur-md">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-400">Karakter Wilayah</p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-300">{{ config('landing.profil.karakter') }}</p>
                    </div>
                </div>

                <div class="absolute -bottom-5 -right-3 hidden rounded-xl border border-white/10 bg-slate-900 px-5 py-4 shadow-xl sm:block">
                    <p class="font-display text-xl font-bold text-white">± 11,89 <span class="text-amber-400">km²</span></p>
                    <p class="text-[11px] font-semibold text-slate-400">Desa terluas di Kec. Pragaan</p>
                </div>
            </div>

            <!-- Teks kanan -->
            <div class="order-1 lg:order-2">
                <span class="reveal inline-flex items-center gap-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400">
                    <span class="h-px w-8 bg-amber-400/60"></span> Profil Desa
                </span>
                <h2 class="reveal mt-4 font-display text-3xl font-bold tracking-tight text-white sm:text-4xl reveal-delay-1">
                    Dari <span class="italic text-amber-400">"Koel"</span>, Kampung Ukiran, Hingga Sentra Madura
                </h2>
                <p class="reveal mt-5 text-base leading-relaxed text-slate-400 reveal-delay-2">
                    {{ config('landing.profil.ringkas') }}
                </p>

                <div class="reveal mt-8 space-y-3 reveal-delay-2">
                    @foreach (config('landing.profil.sejarah') as $poin)
                        <div class="flex gap-4 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur transition-all duration-300 hover:border-amber-400/25 hover:bg-white/10">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-400/10 text-amber-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <p class="text-sm leading-relaxed text-slate-300">{{ $poin }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Ikon ciri khas -->
                <div class="reveal mt-8 flex flex-wrap gap-2 reveal-delay-3">
                    @foreach (config('landing.profil.ikon') as $ikon)
                        <span class="rounded-full border border-amber-400/20 bg-amber-400/5 px-3.5 py-1.5 text-xs font-bold text-amber-300 transition-all duration-200 hover:scale-105 hover:bg-amber-400/15">
                            ✦ {{ $ikon }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
