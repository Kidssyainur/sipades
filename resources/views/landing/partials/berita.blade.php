<section id="berita" class="relative scroll-mt-24 bg-slate-900 py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-end">
            <div>
                <span class="reveal inline-flex items-center gap-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400">
                    <span class="h-px w-8 bg-amber-400/60"></span> Berita & Kegiatan
                </span>
                <h2 class="reveal mt-4 font-display text-3xl font-bold tracking-tight text-white sm:text-4xl reveal-delay-1">
                    Jejak <span class="italic text-amber-400">Karduluk</span> Terkini
                </h2>
            </div>
            <p class="reveal max-w-md text-sm leading-relaxed text-slate-500 reveal-delay-2">
                Perjalanan desa dari pemberitaan media, program pemerintah, hingga prestasi warga — disusun kronologis dan dikelola langsung dari panel admin.
            </p>
        </div>

        @php
            $beritaList = \App\Models\Berita::query()
                ->published()
                ->terbaru()
                ->limit(6)
                ->get();
        @endphp

        <div class="reveal relative mt-14 space-y-6 before:absolute before:left-[7px] before:top-2 before:h-full before:w-px before:bg-gradient-to-b before:from-amber-400/60 before:via-white/15 before:to-transparent md:before:left-1/2">
            @forelse ($beritaList as $index => $b)
                <div class="relative flex gap-6 md:w-1/2 {{ $index % 2 === 0 ? 'md:pr-12' : 'md:ml-auto md:flex-row-reverse md:pl-12' }}">
                    <span class="absolute left-0 top-2 flex h-4 w-4 items-center justify-center md:left-auto {{ $index % 2 === 0 ? 'md:-right-2' : 'md:-left-2' }}">
                        <span class="absolute h-4 w-4 animate-ping rounded-full bg-amber-400/40"></span>
                        <span class="relative h-4 w-4 rounded-full border-4 border-slate-900 bg-amber-400"></span>
                    </span>

                    <article class="group w-full overflow-hidden rounded-2xl border border-white/10 bg-white/5 backdrop-blur transition-all duration-300 hover:-translate-y-1 hover:border-amber-400/25 hover:bg-white/10">
                        @if ($b->gambar)
                            <a href="{{ route('berita.show', $b->slug) }}" class="block overflow-hidden">
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($b->gambar) }}"
                                    alt="{{ $b->judul }}"
                                    loading="lazy"
                                    class="aspect-video w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                >
                            </a>
                        @endif

                        <div class="p-6">
                            <div class="flex flex-wrap items-center gap-2">
                                <time class="inline-flex items-center gap-2 rounded-full bg-amber-400/10 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-amber-300">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $b->tanggal->translatedFormat('j F Y') }}
                                </time>
                                @if ($b->kategori)
                                    <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-300">{{ $b->kategori }}</span>
                                @endif
                            </div>
                            <h3 class="mt-3 font-display text-lg font-bold leading-snug text-white">
                                <a href="{{ route('berita.show', $b->slug) }}" class="transition-colors hover:text-amber-300">
                                    {{ $b->judul }}
                                </a>
                            </h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-400">{{ $b->ringkasan_fallback }}</p>
                            <a href="{{ route('berita.show', $b->slug) }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-400 transition-all hover:gap-3">
                                Baca Selengkapnya
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-white/15 bg-white/5 p-10 text-center">
                    <p class="text-sm text-slate-400">Belum ada berita yang diterbitkan. Admin dapat menambahkannya melalui panel <span class="font-semibold text-amber-300">Manajemen Berita</span>.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
