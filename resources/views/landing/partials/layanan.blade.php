<section id="layanan" class="relative scroll-mt-24 overflow-hidden bg-gradient-to-br from-slate-900 via-slate-950 to-slate-950 py-20 sm:py-28">
    <div class="pointer-events-none absolute inset-0 opacity-[0.04] pattern-ornament"></div>
    <div class="pointer-events-none absolute -left-24 top-0 h-80 w-80 rounded-full bg-emerald-500/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-amber-500/10 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <span class="reveal inline-flex items-center gap-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400">
                <span class="h-px w-8 bg-amber-400/60"></span> Layanan Desa <span class="h-px w-8 bg-amber-400/60"></span>
            </span>
            <h2 class="reveal mt-4 font-display text-3xl font-bold tracking-tight text-white sm:text-4xl reveal-delay-1">
                Alur Pelayanan yang <span class="italic text-amber-400">Jelas & Transparan</span>
            </h2>
            <p class="reveal mt-4 text-slate-400 reveal-delay-2">
                Tiga alur utama pelayanan di kantor desa — kini juga dapat dimulai secara online melalui SIPADES.
            </p>
        </div>

        <div class="mt-14 grid gap-5 lg:grid-cols-3">
            @foreach (config('landing.layanan') as $index => $l)
                <div class="reveal group flex flex-col rounded-2xl border border-white/10 bg-white/5 p-7 backdrop-blur transition-all duration-300 hover:-translate-y-1.5 hover:border-emerald-400/30 hover:bg-white/10"
                     style="--reveal-delay: {{ $index * 120 }}ms">
                    <div class="flex items-center justify-between">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 font-display text-lg font-bold text-white shadow-lg shadow-emerald-950/40">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <svg class="h-8 w-8 text-white/10 transition-transform duration-500 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>

                    <h3 class="mt-5 font-display text-lg font-bold text-white">{{ $l['judul'] }}</h3>
                    <p class="mt-1.5 text-[13px] leading-relaxed text-slate-400">{{ $l['deskripsi'] }}</p>

                    <ol class="mt-5 space-y-2.5 border-t border-white/10 pt-5">
                        @foreach ($l['langkah'] as $step => $langkah)
                            <li class="flex items-start gap-3 text-[13px] leading-relaxed text-slate-300">
                                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-emerald-500/15 text-[10px] font-bold text-emerald-400">{{ $step + 1 }}</span>
                                {{ $langkah }}
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endforeach
        </div>

        <!-- CTA -->
        <div class="reveal mt-12 flex flex-col items-center justify-between gap-6 rounded-2xl border border-white/10 bg-white/5 px-8 py-8 backdrop-blur sm:flex-row reveal-delay-1">
            <div class="flex items-center gap-5">
                <span class="hidden h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-950/40 sm:flex">
                    <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <div>
                    <h3 class="font-display text-xl font-bold text-white sm:text-2xl">Tidak perlu antre — urus surat secara online</h3>
                    <p class="mt-1 text-sm text-slate-400">Daftar akun warga, ajukan permohonan, dan lacak statusnya kapan saja, dari mana saja.</p>
                </div>
            </div>
            <div class="flex shrink-0 flex-wrap gap-3">
                <a href="{{ route('registrasi') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 px-6 py-3 text-sm font-bold text-slate-950 shadow-xl shadow-amber-950/30 transition-all duration-300 hover:brightness-110 active:scale-95">
                    Daftar & Ajukan
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </a>
                <a href="{{ route('portal.login') }}"
                   class="inline-flex items-center rounded-full border border-white/15 px-6 py-3 text-sm font-bold text-slate-200 transition hover:bg-white/10 hover:text-white">
                    Masuk Warga
                </a>
            </div>
        </div>
    </div>
</section>
