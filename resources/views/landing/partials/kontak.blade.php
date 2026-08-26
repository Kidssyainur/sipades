<section id="kontak" class="relative scroll-mt-24 bg-slate-950 py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-14 lg:grid-cols-2 lg:gap-16">
            <!-- Kiri: info kontak -->
            <div>
                <span class="reveal inline-flex items-center gap-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400">
                    <span class="h-px w-8 bg-amber-400/60"></span> Hubungi Kami
                </span>
                <h2 class="reveal mt-4 font-display text-3xl font-bold tracking-tight text-white sm:text-4xl reveal-delay-1">
                    Kantor Desa <span class="italic text-amber-400">Selalu Terbuka</span>
                </h2>
                <p class="reveal mt-5 max-w-lg text-base leading-relaxed text-slate-400 reveal-delay-2">
                    Kunjungi Balai Desa untuk layanan langsung, atau hubungi kami melalui kanal berikut untuk informasi wisata ukir dan layanan administrasi.
                </p>

                <div class="reveal mt-9 space-y-3 reveal-delay-2">
                    <!-- Alamat -->
                    <div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur transition-all duration-300 hover:border-amber-400/25 hover:bg-white/10">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 shadow-lg shadow-amber-950/40">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Alamat</p>
                            <p class="mt-1 text-sm font-semibold leading-relaxed text-slate-300">{{ config('landing.kontak.alamat') }}</p>
                        </div>
                    </div>

                    <!-- Telepon -->
                    <div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur transition-all duration-300 hover:border-amber-400/25 hover:bg-white/10">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 shadow-lg shadow-amber-950/40">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Telepon / WhatsApp</p>
                            <a href="https://wa.me/6285257379290" target="_blank" rel="noopener"
                               class="mt-1 inline-flex flex-wrap items-center gap-2 text-sm font-bold text-amber-300 transition hover:text-amber-200 hover:underline">
                                {{ config('landing.kontak.telepon') }}
                                <span class="rounded-full border border-emerald-400/25 bg-emerald-500/10 px-2 py-0.5 text-[10px] font-bold uppercase text-emerald-400">Kontak Wisata Ukir</span>
                            </a>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur transition-all duration-300 hover:border-amber-400/25 hover:bg-white/10">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 shadow-lg shadow-amber-950/40">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Email</p>
                            <a href="mailto:{{ config('landing.kontak.email') }}"
                               class="mt-1 text-sm font-bold text-amber-300 transition hover:text-amber-200 hover:underline">
                                {{ config('landing.kontak.email') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kanal resmi -->
                <div class="reveal mt-8 flex flex-wrap gap-3 reveal-delay-3">
                    @foreach (config('landing.kontak.kanal') as $kanal)
                        <a href="{{ $kanal['url'] }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-full border border-white/15 px-5 py-2.5 text-xs font-bold text-slate-300 transition-all duration-300 hover:border-amber-400/40 hover:bg-white/5 hover:text-white">
                            <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.5-1.5M10.172 13.828a4 4 0 010-5.656l3-3a4 4 0 015.656 5.656l-1.5 1.5"/></svg>
                            {{ $kanal['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Kanan: peta / visual -->
            <div class="reveal relative reveal-delay-2">
                <div class="sticky top-28 overflow-hidden rounded-[1.75rem] border border-white/10 shadow-2xl shadow-black/50">
                    <div class="relative aspect-[4/3]">
                        <img src="{{ asset('assets/batu_sulung.webp') }}"
                             alt="Pesisir selatan Madura, wisata Batu Sulung Desa Karduluk"
                             class="absolute inset-0 h-full w-full object-cover"
                             loading="lazy"
                             onerror="this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/10 to-transparent"></div>

                        <div class="absolute inset-x-4 bottom-4 rounded-2xl border border-white/10 bg-slate-950/70 p-5 backdrop-blur-md">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-400/10 text-amber-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-400">Lokasi</p>
                                    <p class="text-sm font-bold text-white">Pulau Madura · Pesisir Selatan</p>
                                    <p class="text-[11px] text-slate-500">Kode Pos {{ config('landing.kode_pos') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
