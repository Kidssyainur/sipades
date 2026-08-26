<x-layouts.landing :title="$berita->judul">

    <article class="relative bg-slate-950 pb-20 pt-16 sm:pt-24">
        <!-- Ornamen halus -->
        <div class="pointer-events-none absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-amber-400/5 to-transparent" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}#berita" class="reveal inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500 transition-colors hover:text-amber-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Berita
            </a>

            <div class="reveal mt-8 flex flex-wrap items-center gap-3 text-xs font-bold uppercase tracking-wider reveal-delay-1">
                <span class="inline-flex items-center gap-2 rounded-full bg-amber-400/10 px-3 py-1.5 text-amber-300">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $berita->tanggal->translatedFormat('j F Y') }}
                </span>
                @if ($berita->kategori)
                    <span class="rounded-full bg-white/5 px-3 py-1.5 text-slate-300">{{ $berita->kategori }}</span>
                @endif
                @if ($berita->penulis)
                    <span class="rounded-full bg-white/5 px-3 py-1.5 text-slate-400">{{ $berita->penulis }}</span>
                @endif
            </div>

            <h1 class="reveal mt-5 font-display text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl lg:text-[2.75rem] reveal-delay-2">
                {{ $berita->judul }}
            </h1>

            @if ($berita->gambar)
                <div class="reveal mt-8 overflow-hidden rounded-3xl border border-white/10 reveal-delay-2">
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($berita->gambar) }}"
                        alt="{{ $berita->judul }}"
                        class="aspect-video w-full object-cover"
                    >
                </div>
            @endif

            <div
                class="reveal mx-auto mt-10 max-w-3xl text-[15px] leading-relaxed [&_p]:mb-5 [&_p]:text-slate-300 [&_strong]:font-semibold [&_strong]:text-white [&_a]:font-medium [&_a]:text-amber-400 [&_a]:underline [&_a]:underline-offset-4 [&_a]:transition-colors [&_a:hover]:text-amber-300 [&_h1]:mb-4 [&_h1]:mt-10 [&_h1]:font-display [&_h1]:text-3xl [&_h1]:font-bold [&_h1]:text-white [&_h2]:mb-3 [&_h2]:mt-9 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-white [&_h3]:mb-3 [&_h3]:mt-8 [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-bold [&_h3]:text-white [&_h4]:mb-2 [&_h4]:mt-6 [&_h4]:font-display [&_h4]:text-lg [&_h4]:font-bold [&_h4]:text-white [&_ul]:mb-5 [&_ul]:list-disc [&_ul]:space-y-1.5 [&_ul]:pl-6 [&_ol]:mb-5 [&_ol]:list-decimal [&_ol]:space-y-1.5 [&_ol]:pl-6 [&_blockquote]:my-7 [&_blockquote]:border-l-4 [&_blockquote]:border-amber-400/60 [&_blockquote]:bg-white/5 [&_blockquote]:py-3 [&_blockquote]:pl-5 [&_blockquote]:italic [&_blockquote]:text-slate-300 [&_hr]:my-8 [&_hr]:border-white/10 [&_img]:my-6 [&_img]:rounded-2xl [&_pre]:mb-5 [&_pre]:overflow-x-auto [&_pre]:rounded-xl [&_pre]:bg-slate-900 [&_pre]:p-4 [&_pre]:text-sm [&_code]:rounded [&_code]:bg-white/10 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:font-mono [&_code]:text-amber-200 [&_pre_code]:bg-transparent [&_table]:mb-6 [&_table]:w-full [&_table]:overflow-hidden [&_table]:rounded-xl [&_table]:text-sm [&_th]:border [&_th]:border-white/10 [&_th]:bg-white/5 [&_th]:px-4 [&_th]:py-2.5 [&_th]:text-left [&_th]:font-semibold [&_th]:text-white [&_td]:border [&_td]:border-white/10 [&_td]:px-4 [&_td]:py-2.5 [&_td]:align-top"
                reveal-delay-3
            >
                {!! \Filament\Forms\Components\RichEditor\RichContentRenderer::make()->content($berita->isi)->toHtml() !!}
            </div>

            <div class="mt-12 flex items-center gap-2 border-t border-white/10 pt-6 text-xs text-slate-500">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Dilihat {{ number_format($berita->views) }} kali
            </div>
        </div>
    </article>

    @if ($beritaTerbaru->isNotEmpty())
        <section class="bg-slate-900 py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between gap-4">
                    <h2 class="font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Berita <span class="italic text-amber-400">Lainnya</span>
                    </h2>
                    <a href="{{ route('home') }}#berita" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-400 transition-all hover:gap-3">
                        Semua Berita
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($beritaTerbaru as $item)
                        <a href="{{ route('berita.show', $item->slug) }}" class="group overflow-hidden rounded-2xl border border-white/10 bg-white/5 backdrop-blur transition-all duration-300 hover:-translate-y-1 hover:border-amber-400/25 hover:bg-white/10">
                            @if ($item->gambar)
                                <div class="overflow-hidden">
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->gambar) }}"
                                        alt="{{ $item->judul }}"
                                        loading="lazy"
                                        class="aspect-video w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    >
                                </div>
                            @endif
                            <div class="p-5">
                                <time class="text-[11px] font-bold uppercase tracking-wide text-amber-300">{{ $item->tanggal->translatedFormat('j F Y') }}</time>
                                <h3 class="mt-2 font-display text-base font-bold leading-snug text-white transition-colors group-hover:text-amber-300">{{ $item->judul }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-landing-layout>
