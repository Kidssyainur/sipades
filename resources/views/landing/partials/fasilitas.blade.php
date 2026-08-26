<section id="fasilitas" class="relative scroll-mt-24 overflow-hidden bg-slate-950 py-20 sm:py-28">
    <div class="pointer-events-none absolute -right-40 bottom-20 h-96 w-96 rounded-full bg-emerald-500/5 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <!-- Kiri: teks -->
            <div>
                <span class="reveal inline-flex items-center gap-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400">
                    <span class="h-px w-8 bg-amber-400/60"></span> Pendidikan & Fasilitas
                </span>
                <h2 class="reveal mt-4 font-display text-3xl font-bold tracking-tight text-white sm:text-4xl reveal-delay-1">
                    Fasilitas yang <span class="italic text-amber-400">Menopang Generasi</span>
                </h2>
                <p class="reveal mt-5 text-base leading-relaxed text-slate-400 reveal-delay-2">
                    Kehidupan sosial Karduluk yang agamis ditandai dengan berkembangnya lembaga pendidikan berbasis pesantren, didukung sekolah formal dan layanan kesehatan yang dikelola bersama PKK Desa.
                </p>

                <ul class="mt-8 space-y-3">
                    @foreach (config('landing.fasilitas') as $index => $f)
                        <li class="reveal flex items-start gap-4 rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur transition-all duration-300 hover:border-amber-400/25 hover:bg-white/10"
                            style="--reveal-delay: {{ $index * 80 }}ms">
                            <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-slate-900 text-lg">
                                @php $icons = ['🏫', '🎓', '🏥', '🏛️']; @endphp
                                {{ $icons[$index] ?? '🏛️' }}
                            </span>
                            <p class="text-sm font-medium leading-relaxed text-slate-300">{{ $f }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Kanan: visual + prestasi -->
            <div class="reveal reveal-delay-2">
                <div class="relative aspect-[16/11] w-full overflow-hidden rounded-[1.75rem] border border-white/10 shadow-2xl shadow-black/50">
                    <img src="{{ asset('assets/prestasi_desa.webp') }}"
                         alt="Penghargaan dan prestasi Desa Karduluk"
                         class="absolute inset-0 h-full w-full object-cover"
                         loading="lazy"
                         onerror="if(!this.dataset.f){this.dataset.f=1;this.src='{{ asset('assets/prestasi_desa.jpg') }}'}else{this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();}">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 rounded-2xl border border-white/10 bg-slate-950/70 p-5 backdrop-blur-md">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-400">Prestasi Desa</p>
                        <ul class="mt-2.5 space-y-2">
                            @foreach (config('landing.prestasi') as $p)
                                <li class="flex items-start gap-2 text-[13px] font-medium text-slate-200">
                                    <span class="text-amber-400">🏆</span> {{ $p }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
