<section id="layanan" class="relative scroll-mt-24 overflow-hidden bg-gradient-to-br from-slate-900 via-slate-950 to-slate-950 py-20 sm:py-28">
    <span id="layanan-online" class="absolute -top-28"></span>
    <span id="layanan-offline" class="absolute -top-28"></span>
    <span id="alur-offline" class="absolute -top-28"></span>

    <!-- Background Accents -->
    <div class="pointer-events-none absolute inset-0 opacity-[0.04] pattern-ornament"></div>
    <div class="pointer-events-none absolute -left-24 top-0 h-96 w-96 rounded-full bg-emerald-500/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 top-1/2 h-96 w-96 rounded-full bg-amber-500/10 blur-3xl"></div>
    <div class="pointer-events-none absolute left-1/3 bottom-0 h-80 w-80 rounded-full bg-teal-500/10 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="mx-auto max-w-3xl text-center">
            <span class="reveal inline-flex items-center gap-2.5 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400">
                <span class="h-px w-8 bg-amber-400/60"></span> Layanan Administrasi Desa <span class="h-px w-8 bg-amber-400/60"></span>
            </span>
            <h2 class="reveal mt-4 font-display text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl reveal-delay-1">
                Alur Pengajuan Surat & <span class="italic text-amber-400">Pelayanan Warga</span>
            </h2>
            <p class="reveal mt-4 text-base leading-relaxed text-slate-300 sm:text-lg reveal-delay-2">
                Pemerintah Desa Karduluk menyediakan dua metode pelayanan surat: <span class="font-semibold text-emerald-400">Online via SIPADES</span> (proses cepat 24 jam tanpa antre) atau <span class="font-semibold text-amber-400">Offline di Balai Desa</span> (konvensional tatap muka di loket pelayanan).
            </p>
        </div>

        <!-- ================================================================= -->
        <!-- DUA JALUR PENGAJUAN SURAT (ONLINE VS OFFLINE) -->
        <!-- ================================================================= -->
        <div class="mt-14 grid gap-8 lg:grid-cols-2 items-stretch">
            <!-- JALUR 1: ONLINE (SIPADES) -->
            <div class="reveal relative flex flex-col justify-between rounded-3xl border border-emerald-500/30 bg-gradient-to-b from-emerald-950/20 via-slate-900/60 to-slate-900/80 p-7 sm:p-8 backdrop-blur transition-all duration-300 hover:border-emerald-400/50 hover:shadow-2xl hover:shadow-emerald-950/50">
                <div>
                    <!-- Header Kartu Online -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-400">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Jalur Online · Rekomendasi Modern
                        </span>
                        <span class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-300">
                            Akses 24/7 Mandiri
                        </span>
                    </div>

                    <h3 class="mt-5 font-display text-2xl font-bold text-white sm:text-3xl">
                        Alur Online via <span class="text-emerald-400">SIPADES</span>
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-300">
                        Urus surat secara praktis dari rumah via smartphone atau komputer. Berkas diunggah secara digital, status terpantau real-time, dan surat PDF ber-TTE resmi langsung dapat diunduh.
                    </p>

                    <!-- Stepper Alur Online (5 Langkah) -->
                    <div class="mt-6 space-y-3.5 border-t border-white/10 pt-6">
                        @php
                            $onlineSteps = config('landing.alur_surat_online.langkah') ?? [];
                        @endphp
                        @foreach ($onlineSteps as $i => $step)
                            <div class="group flex items-start gap-3.5 rounded-xl border border-white/5 bg-white/[0.03] p-3.5 transition hover:border-emerald-400/30 hover:bg-white/[0.06]">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-500/20 text-xs font-bold text-emerald-300 group-hover:bg-emerald-500 group-hover:text-slate-950 transition">
                                    {{ $i + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-white group-hover:text-emerald-300 transition">
                                        {{ $step['judul'] }}
                                    </h4>
                                    <p class="mt-0.5 text-xs leading-relaxed text-slate-400">
                                        {{ $step['deskripsi'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Keunggulan Singkat Box -->
                    <div class="mt-6 grid grid-cols-2 gap-2.5 rounded-2xl border border-emerald-500/20 bg-emerald-950/30 p-4 text-xs text-slate-300">
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-bold">✓</span> Tanpa antre di balai desa
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-bold">✓</span> Notifikasi WhatsApp real-time
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-bold">✓</span> TTE Kades resmi ber-QR Code
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-bold">✓</span> Langsung unduh & cetak PDF
                        </div>
                    </div>
                </div>

                <!-- CTA Online -->
                <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-white/10 pt-6">
                    <a href="{{ route('registrasi') }}"
                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-950/40 transition hover:brightness-110 active:scale-95 text-center">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Daftar & Ajukan Online
                    </a>
                    <a href="{{ route('portal.login') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-white/20 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10 hover:text-white">
                        Masuk Portal
                    </a>
                </div>
            </div>

            <!-- JALUR 2: OFFLINE (BALAI DESA / SEBELUMNYA) -->
            <div class="reveal relative flex flex-col justify-between rounded-3xl border border-amber-500/30 bg-gradient-to-b from-amber-950/20 via-slate-900/60 to-slate-900/80 p-7 sm:p-8 backdrop-blur transition-all duration-300 hover:border-amber-400/50 hover:shadow-2xl hover:shadow-amber-950/50 reveal-delay-1">
                <div>
                    <!-- Header Kartu Offline -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-3.5 py-1 text-xs font-bold text-amber-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Jalur Konvensional · Balai Desa
                        </span>
                        <span class="rounded-xl border border-amber-500/20 bg-amber-500/10 px-3 py-1 text-xs font-bold text-amber-300">
                            Tatap Muka di Loket
                        </span>
                    </div>

                    <h3 class="mt-5 font-display text-2xl font-bold text-white sm:text-3xl">
                        Alur Pengajuan <span class="text-amber-400">Offline</span> (Sebelumnya)
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-300">
                        Tata cara pengurusan surat fisik dengan datang langsung ke loket Balai Desa Karduluk seperti sebelumnya. Cocok bagi warga lansia atau yang memerlukan pendampingan langsung.
                    </p>

                    <!-- Stepper Alur Offline (6 Langkah) -->
                    <div class="mt-6 space-y-3 border-t border-white/10 pt-6">
                        @php
                            $offlineSteps = config('landing.alur_surat_offline.langkah') ?? [];
                        @endphp
                        @foreach ($offlineSteps as $i => $step)
                            <div class="group flex items-start gap-3 rounded-xl border border-white/5 bg-white/[0.03] p-3 transition hover:border-amber-400/30 hover:bg-white/[0.06]">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-amber-500/20 text-xs font-bold text-amber-300 group-hover:bg-amber-400 group-hover:text-slate-950 transition">
                                    {{ $i + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <h4 class="text-xs font-bold text-white group-hover:text-amber-300 transition sm:text-sm">
                                            {{ $step['judul'] }}
                                        </h4>
                                        @if (!empty($step['durasi']))
                                            <span class="shrink-0 text-[10px] text-amber-400/90 font-medium">
                                                {{ $step['durasi'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-0.5 text-[11px] leading-relaxed text-slate-400 sm:text-xs">
                                        {{ $step['deskripsi'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Info Box Jam Operasional & Dokumen Fisik -->
                    <div class="mt-6 rounded-2xl border border-amber-500/20 bg-amber-950/30 p-4 text-xs space-y-2">
                        <div class="flex items-start gap-2 text-slate-300">
                            <span class="font-bold text-amber-400 shrink-0">🕒 Jam Loket:</span>
                            <span class="leading-relaxed">Senin–Kamis 08.00–14.30 WIB · Jumat 08.00–11.30 & 13.00–14.30 WIB</span>
                        </div>
                        <div class="flex items-start gap-2 text-slate-300 border-t border-white/5 pt-2">
                            <span class="font-bold text-amber-400 shrink-0">📋 Berkas Wajib:</span>
                            <span class="leading-relaxed">Surat Pengantar RT/RW asli, Fotokopi KTP & KK, berkas penunjang jenis surat</span>
                        </div>
                        <div class="flex items-start gap-2 text-amber-300/90 border-t border-white/5 pt-2">
                            <span class="font-bold text-amber-400 shrink-0">ℹ️ Catatan:</span>
                            <span class="leading-relaxed">Tanda tangan basah Kepala Desa bergantung kehadiran fisik di kantor (bisa 1–3 hari kerja bila dinas luar).</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Offline -->
                <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-white/10 pt-6">
                    <div class="text-xs text-slate-400">
                        📍 <strong>Lokasi:</strong> Ruang Pelayanan Balai Desa Karduluk, Pragaan
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-slate-300">
                        Biaya Pelayanan: Gratis (Rp 0)
                    </span>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TABEL PERBANDINGAN FITUR (OFFLINE VS ONLINE) -->
        <!-- ================================================================= -->
        <div class="mt-14 rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur sm:p-8">
            <div class="text-center max-w-2xl mx-auto mb-6">
                <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400">Ringkasan Perbedaan</span>
                <h3 class="mt-1 font-display text-xl font-bold text-white sm:text-2xl">
                    Perbandingan: Pengajuan Offline vs SIPADES Online
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-[11px] font-bold uppercase tracking-wider">
                            <th class="py-3 px-3 text-slate-400 w-1/4">Aspek</th>
                            <th class="py-3 px-3 text-amber-300 bg-amber-500/10 rounded-tl-xl w-3/8">Alur Offline (Balai Desa / Sebelumnya)</th>
                            <th class="py-3 px-3 text-emerald-300 bg-emerald-500/10 rounded-tr-xl w-3/8">Alur Online (SIPADES)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach (config('landing.komparasi_layanan') ?? [] as $row)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="py-3 px-3 font-semibold text-white">{{ $row['aspek'] }}</td>
                                <td class="py-3 px-3 text-slate-300 bg-amber-500/[0.02]">
                                    <span class="text-amber-400 mr-1.5">•</span>{{ $row['offline'] }}
                                </td>
                                <td class="py-3 px-3 text-emerald-200 bg-emerald-500/[0.03] font-medium">
                                    <span class="text-emerald-400 mr-1.5 font-bold">✓</span>{{ $row['online'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- LAYANAN DESA LAINNYA (PENGADUAN & MUSRENBANGDES) -->
        <!-- ================================================================= -->
        <div class="mt-20 border-t border-white/10 pt-16">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Pelayanan Aspirasi & Pembangunan</span>
                <h3 class="mt-2 font-display text-2xl font-bold text-white sm:text-3xl">
                    Layanan Pengaduan & Pembangunan Desa
                </h3>
                <p class="mt-2 text-sm text-slate-400">Selain surat-menyurat, warga dapat menyampaikan aduan dan usulan pembangunan melalui mekanisme berikut:</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                @php
                    $otherServices = array_slice(config('landing.layanan') ?? [], 1);
                @endphp
                @foreach ($otherServices as $idx => $svc)
                    <div class="reveal rounded-2xl border border-white/10 bg-white/5 p-6 sm:p-7 backdrop-blur transition-all duration-300 hover:border-emerald-400/30 hover:bg-white/10"
                         style="--reveal-delay: {{ $idx * 100 }}ms">
                        <div class="flex items-center justify-between">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 font-display text-sm font-bold text-white shadow-md">
                                {{ str_pad($idx + 2, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="rounded-full bg-emerald-500/10 px-3 py-0.5 text-xs font-semibold text-emerald-400">Prosedur Resmi</span>
                        </div>

                        <h4 class="mt-4 font-display text-lg font-bold text-white">{{ $svc['judul'] }}</h4>
                        <p class="mt-1 text-xs leading-relaxed text-slate-400">{{ $svc['deskripsi'] }}</p>

                        <ol class="mt-4 space-y-2 border-t border-white/10 pt-4">
                            @foreach ($svc['langkah'] as $stepIdx => $lgh)
                                <li class="flex items-start gap-2.5 text-xs text-slate-300">
                                    <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-md bg-emerald-500/15 text-[10px] font-bold text-emerald-400">
                                        {{ $stepIdx + 1 }}
                                    </span>
                                    <span class="leading-relaxed">{{ $lgh }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- CTA: URUS SURAT ONLINE -->
        <!-- ================================================================= -->
        <div class="reveal mt-16 flex flex-col items-center justify-between gap-6 rounded-2xl border border-white/10 bg-gradient-to-r from-emerald-950/40 via-slate-900/60 to-slate-900/60 px-8 py-8 backdrop-blur sm:flex-row reveal-delay-1 shadow-2xl">
            <div class="flex items-center gap-5">
                <span class="hidden h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-950/40 sm:flex">
                    <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <div>
                    <h3 class="font-display text-xl font-bold text-white sm:text-2xl">Urus Surat Lebih Cepat Tanpa Antre</h3>
                    <p class="mt-1 text-sm text-slate-300">Daftarkan akun kependudukan Anda di SIPADES, ajukan surat kapan saja, dan pantau statusnya dari ponsel Anda.</p>
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

