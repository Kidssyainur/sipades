<section id="beranda" class="relative overflow-hidden bg-slate-950 pb-24 pt-32 sm:pb-28 sm:pt-36 lg:pt-40">
    <!-- Background foto ukir + overlay -->
    <div class="absolute inset-0">
        <img src="{{ asset('assets/desa_karduluk.webp') }}"
             alt="Pemandangan Desa Karduluk"
             class="h-full w-full object-cover opacity-20"
             onerror="if(!this.dataset.f){this.dataset.f=1;this.src='{{ asset('assets/desa_karduluk.jpg') }}'}else{this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();}">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-950/90 to-slate-950"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-slate-950/60"></div>
    </div>

    <!-- Glow orbs -->
    <div class="pointer-events-none absolute -left-32 top-24 h-80 w-80 rounded-full bg-amber-500/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-10 h-96 w-96 rounded-full bg-emerald-500/10 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-12">
            <!-- Kiri: copy -->
            <div class="max-w-2xl lg:col-span-7">
                <div class="reveal inline-flex items-center gap-2.5 rounded-full border border-amber-400/25 bg-amber-400/10 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.18em] text-amber-300">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    </span>
                    Kec. Pragaan · Kab. Sumenep · Jawa Timur
                </div>

                <h1 class="reveal mt-6 font-display text-[2.6rem] font-bold leading-[1.1] tracking-tight text-white sm:text-6xl reveal-delay-1">
                    Pusaka Ukir Madura,
                    <span class="italic text-amber-400">Karduluk</span>
                </h1>

                <p class="reveal mt-6 max-w-xl text-base leading-relaxed text-slate-400 sm:text-lg reveal-delay-2">
                    Satu-satunya sentra kerajinan ukir kayu di Madura — karya turun-temurun yang telah menembus pasar
                    mancanegara, dipadu wisata pesisir dan layanan desa yang kini dapat diurus secara online.
                </p>

                <div class="reveal mt-9 flex flex-wrap items-center gap-3 reveal-delay-3">
                    <a href="{{ route('registrasi') }}"
                       class="group inline-flex items-center gap-2.5 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 px-7 py-3.5 text-sm font-bold text-slate-950 shadow-xl shadow-amber-950/40 transition-all duration-300 hover:shadow-amber-900/50 hover:brightness-110 active:scale-95">
                        Ajukan Surat Online
                        <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </a>
                    <a href="#profil"
                       class="inline-flex items-center gap-2 rounded-full border border-white/15 px-7 py-3.5 text-sm font-bold text-slate-200 transition-all duration-300 hover:border-amber-400/40 hover:bg-white/5 hover:text-white">
                        Kenali Desa
                    </a>
                </div>

                <!-- Mini stats -->
                <dl class="reveal mt-12 grid max-w-md grid-cols-3 gap-6 border-t border-white/10 pt-8 reveal-delay-4">
                    @foreach (array_slice(config('landing.statistik'), 0, 3) as $stat)
                        <div>
                            <dd class="font-display text-2xl font-bold text-white sm:text-3xl">
                                {{ $stat['nilai'] }}<span class="text-amber-400">{{ $stat['suffix'] }}</span>
                            </dd>
                            <dt class="mt-1 text-xs font-medium text-slate-500">{{ $stat['label'] }}</dt>
                        </div>
                    @endforeach
                </dl>
            </div>

            <!-- Kanan: kartu visual -->
            <div class="relative hidden lg:col-span-5 lg:block">
                <div class="reveal reveal-delay-3 relative">
                    <div class="absolute -inset-5 rounded-[2.5rem] bg-gradient-to-br from-amber-500/20 to-emerald-500/10 blur-2xl"></div>

                    <div class="relative aspect-[4/3] w-full overflow-hidden rounded-[1.75rem] border border-white/10 shadow-2xl shadow-black/50">
                        <img src="{{ asset('assets/seni_ukir.webp') }}"
                             alt="Seni ukiran kayu khas Karduluk"
                             class="absolute inset-0 h-full w-full object-cover"
                             loading="lazy"
                             onerror="if(!this.dataset.f){this.dataset.f=1;this.src='{{ asset('assets/seni_ukir.jpg') }}'}else{this.onerror=null;this.parentElement.classList.add('bg-gradient-to-br','from-slate-800','to-slate-900');this.remove();}">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/10 to-transparent"></div>

                        <div class="absolute inset-x-0 bottom-0 p-6">
                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-400">Tradisi Leluhur</p>
                            <h3 class="mt-1 font-display text-xl font-semibold text-white">Seni Ukir Turun-temurun</h3>
                        </div>
                    </div>

                    <!-- Floating: Dana Desa -->
                    <div class="absolute -left-8 -top-7 animate-float rounded-xl border border-white/10 bg-slate-900/90 px-4 py-3 shadow-xl backdrop-blur-xl">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Dana Desa 2025</p>
                        <p class="text-sm font-extrabold text-amber-400">Rp 2,2 Miliar</p>
                    </div>

                    <!-- Floating: pengrajin -->
                    <div class="absolute -bottom-7 -right-5 animate-float-slow rounded-xl border border-white/10 bg-slate-900/90 px-5 py-3 shadow-xl backdrop-blur-xl">
                        <p class="font-display text-xl font-bold text-white">600<span class="text-amber-400">+</span></p>
                        <p class="text-[11px] font-semibold text-slate-400">Pengrajin ukir aktif</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave divider -->
    <svg class="absolute inset-x-0 bottom-0 w-full text-slate-900" viewBox="0 0 1440 60" fill="currentColor" preserveAspectRatio="none">
        <path d="M0,40 C240,80 480,0 720,30 C960,60 1200,10 1440,40 L1440,60 L0,60 Z"></path>
    </svg>
</section>
