<section class="relative bg-slate-900 pb-20 pt-16 sm:pb-24 sm:pt-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (config('landing.statistik') as $index => $stat)
                <div class="reveal group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-7 backdrop-blur transition-all duration-300 hover:-translate-y-1 hover:border-amber-400/30 hover:bg-white/10"
                     style="--reveal-delay: {{ $index * 90 }}ms">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-amber-400/5 transition-transform duration-500 group-hover:scale-150"></div>

                    <p class="font-display text-4xl font-bold tracking-tight text-white sm:text-[2.5rem]">
                        @if ($stat['counter'] ?? true)
                            <span data-counter data-target="{{ $stat['target'] }}">0</span>
                        @else
                            <span>{{ $stat['nilai'] }}</span>
                        @endif
                        <span class="text-amber-400">{{ $stat['suffix'] }}</span>
                    </p>
                    <p class="mt-1.5 text-sm font-bold uppercase tracking-wide text-slate-300">{{ $stat['label'] }}</p>
                    <p class="mt-2 text-[13px] leading-relaxed text-slate-500">{{ $stat['deskripsi'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
