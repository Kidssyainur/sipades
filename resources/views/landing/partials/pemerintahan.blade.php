<section id="pemerintahan" class="relative scroll-mt-24 overflow-hidden bg-slate-900 py-20 sm:py-28">
    <div class="pointer-events-none absolute inset-0 opacity-[0.04] pattern-ornament"></div>
    <div class="pointer-events-none absolute -left-32 bottom-0 h-96 w-96 rounded-full bg-emerald-500/5 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <span class="reveal inline-flex items-center gap-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400">
                <span class="h-px w-8 bg-amber-400/60"></span> Pemerintahan <span class="h-px w-8 bg-amber-400/60"></span>
            </span>
            <h2 class="reveal mt-4 font-display text-3xl font-bold tracking-tight text-white sm:text-4xl reveal-delay-1">
                Pemerintahan <span class="italic text-amber-400">Desa</span>
            </h2>
            <p class="reveal mt-4 text-slate-400 reveal-delay-2">
                Melayani 12 dusun dengan struktur perangkat yang jelas dan transparansi anggaran.
            </p>
        </div>

        <!-- Banner kantor desa -->
        <div class="reveal relative mt-12 overflow-hidden rounded-2xl border border-white/10 shadow-xl shadow-black/40">
            <img src="{{ asset('assets/balai_desa.webp') }}"
                 alt="Balai Desa / Kantor Kepala Desa Karduluk"
                 class="h-56 w-full object-cover sm:h-72"
                 loading="lazy"
                 onerror="if(!this.dataset.f){this.dataset.f=1;this.src='{{ asset('assets/balai_desa.jpg') }}'}else{this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();}">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 flex flex-wrap items-end justify-between gap-3 p-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-400">Pusat Pelayanan</p>
                    <h3 class="mt-1 font-display text-lg font-bold text-white">Balai Desa Karduluk</h3>
                </div>
                <span class="rounded-full border border-white/15 bg-slate-950/60 px-4 py-1.5 text-[11px] font-bold text-slate-200 backdrop-blur">
                    📍 Kec. Pragaan, Sumenep
                </span>
            </div>
        </div>

        <div class="mt-6 grid gap-5 lg:grid-cols-3">
            <!-- Kartu Kades -->
            <div class="reveal rounded-2xl border border-white/10 bg-white/5 p-7 backdrop-blur transition-all duration-300 hover:-translate-y-1 hover:border-amber-400/25 hover:bg-white/10">
                <div class="flex items-center gap-4">
                    <span class="relative h-14 w-14 shrink-0 overflow-hidden rounded-2xl border border-white/15 bg-gradient-to-br from-amber-400 to-orange-500 shadow-lg shadow-amber-950/40">
                        <img src="{{ asset('assets/kepala_desa.webp') }}"
                             alt="Foto Kepala Desa Karduluk"
                             class="h-full w-full object-cover"
                             loading="lazy"
                             onerror="if(!this.dataset.f){this.dataset.f=1;this.src='{{ asset('assets/kepala_desa.jpg') }}'}else{this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-amber-400','to-orange-500');this.remove();}">
                        <span class="absolute -bottom-1 -right-1 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-emerald-500 text-[9px] text-white ring-4 ring-slate-900">✓</span>
                    </span>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Kepala Desa</p>
                        <h3 class="font-display text-xl font-bold text-white">{{ config('landing.pemerintahan.kades') }}</h3>
                        <p class="text-xs font-semibold text-amber-400">Periode {{ config('landing.pemerintahan.kades_periode') }}</p>
                    </div>
                </div>
                <p class="mt-5 text-sm leading-relaxed text-slate-400">
                    Memimpin Pemerintah Desa Karduluk bersama perangkat desa, BPD, dan seluruh elemen masyarakat dalam membangun desa sentra ukir yang berdaya saing.
                </p>
                <div class="mt-6 grid grid-cols-2 gap-3 border-t border-white/10 pt-5 text-center">
                    <div class="rounded-xl bg-slate-900/60 px-3 py-3">
                        <p class="font-display text-xl font-bold text-white">12</p>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Dusun</p>
                    </div>
                    <div class="rounded-xl bg-slate-900/60 px-3 py-3">
                        <p class="font-display text-xl font-bold text-white">13</p>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">Desa Tetangga</p>
                    </div>
                </div>
            </div>

            <!-- Struktur organisasi -->
            <div class="reveal rounded-2xl border border-white/10 bg-white/5 p-7 backdrop-blur reveal-delay-1">
                <h3 class="flex items-center gap-2.5 font-display text-lg font-bold text-white">
                    <svg class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4 4 4 0 004 4z"/></svg>
                    Struktur Organisasi
                </h3>
                <ol class="mt-5 space-y-2">
                    @foreach (config('landing.pemerintahan.struktur') as $i => $jabatan)
                        <li class="flex items-center gap-3 rounded-xl bg-slate-900/50 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-800/70">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-amber-400/10 text-[11px] font-bold text-amber-400">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            {{ $jabatan }}
                        </li>
                    @endforeach
                </ol>
            </div>

            <!-- Dana Desa -->
            <div class="reveal flex flex-col rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 p-7 shadow-xl ring-1 ring-white/10 reveal-delay-2">
                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-500/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest text-emerald-400">
                    Transparansi Anggaran
                </span>
                <h3 class="mt-4 font-display text-lg font-bold text-white">Dana Desa {{ config('landing.pemerintahan.dana_desa.tahun') }}</h3>
                <p class="mt-1 text-[13px] text-slate-400">{{ config('landing.pemerintahan.dana_desa.keterangan') }}</p>

                <p class="mt-5 font-display text-3xl font-bold tracking-tight text-amber-400">{{ config('landing.pemerintahan.dana_desa.total') }}</p>

                <ul class="mt-5 space-y-2.5">
                    @foreach (config('landing.pemerintahan.dana_desa.rincian') as $label => $nilai)
                        <li class="flex items-center justify-between rounded-xl bg-white/5 px-4 py-3 text-sm">
                            <span class="font-semibold text-slate-300">{{ $label }}</span>
                            <span class="font-bold text-white">{{ $nilai }}</span>
                        </li>
                    @endforeach
                </ul>

                <p class="mt-auto pt-5 text-[11px] italic leading-relaxed text-slate-500">
                    Data publik K-TV Sumenep (2025). Rincian resmi APBDes dapat dikonfirmasi ke Balai Desa.
                </p>
            </div>
        </div>
    </div>
</section>
