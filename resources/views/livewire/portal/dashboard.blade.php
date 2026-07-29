<div>
    <!-- Hero Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-teal-950 to-emerald-900 p-8 text-white shadow-2xl shadow-emerald-950/20 border border-slate-800">
        <!-- Glowing Radial Overlay Background -->
        <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-emerald-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-teal-500/20 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-500/20 px-3.5 py-1 text-xs font-bold text-emerald-300 border border-emerald-500/30 mb-3 backdrop-blur-md">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Portal Resmi Pelayanan Publik Desa Karduluk</span>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    Selamat Datang, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-teal-200">{{ auth()->user()->name }}</span>
                </h1>
                <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-slate-300 font-medium">
                    <span class="bg-slate-800/80 px-3 py-1 rounded-lg border border-slate-700 font-mono">NIK: {{ auth()->user()->nik ?? '-' }}</span>
                    <span>•</span>
                    <span class="bg-slate-800/80 px-3 py-1 rounded-lg border border-slate-700 font-mono">WhatsApp: {{ auth()->user()->no_hp ?? '-' }}</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('portal.pengajuan.buat') }}"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-900/50 hover:from-emerald-400 hover:to-teal-400 hover:scale-[1.02] transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Buat Pengajuan Surat</span>
                </a>
                <a href="{{ route('portal.profil') }}"
                    class="inline-flex items-center gap-2 rounded-2xl bg-slate-800/90 hover:bg-slate-800 border border-slate-700 px-5 py-3.5 text-sm font-semibold text-slate-200 transition">
                    <span>Profil Saya</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <!-- Total Pengajuan -->
        <a href="{{ route('portal.pengajuan.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:border-slate-200 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pengajuan</span>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $ringkasan['total'] }}</p>
                </div>
                <div class="h-14 w-14 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                <span>Seluruh permohonan surat</span>
            </div>
        </a>

        <!-- Dalam Proses -->
        <a href="{{ route('portal.pengajuan.index') }}?statusFilter=diajukan" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:border-amber-200 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Dalam Proses Approval</span>
                    <p class="mt-2 text-3xl font-extrabold text-amber-600">{{ $ringkasan['proses'] }}</p>
                </div>
                <div class="h-14 w-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-amber-600 font-medium">
                <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Sedang diverifikasi/disetujui</span>
            </div>
        </a>

        <!-- Surat Terbit -->
        <a href="{{ route('portal.surat-terbit.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:border-emerald-200 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Surat Terbit & TTE</span>
                    <p class="mt-2 text-3xl font-extrabold text-emerald-600">{{ $ringkasan['selesai'] }}</p>
                </div>
                <div class="h-14 w-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1.5 text-xs text-emerald-600 font-medium">
                <span>Siap diunduh dengan QR TTE</span>
            </div>
        </a>
    </div>

    <!-- Layanan Cepat Portal -->
    <div class="mt-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-900">Layanan Cepat Portal Warga</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('portal.pengajuan.buat') }}" class="group p-5 bg-white rounded-2xl shadow-sm border border-slate-100 hover:border-emerald-500 hover:shadow-lg hover:-translate-y-0.5 transition-all text-center">
                <div class="h-12 w-12 mx-auto rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block">Ajukan Surat</span>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Katalog Layanan Surat</span>
            </a>

            <a href="{{ route('portal.pengajuan.index') }}" class="group p-5 bg-white rounded-2xl shadow-sm border border-slate-100 hover:border-teal-500 hover:shadow-lg hover:-translate-y-0.5 transition-all text-center">
                <div class="h-12 w-12 mx-auto rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center mb-3 group-hover:bg-teal-600 group-hover:text-white transition-colors shadow-sm">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block">Pengajuan Saya</span>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Riwayat &amp; Status Lacak</span>
            </a>

            <a href="{{ route('portal.surat-terbit.index') }}" class="group p-5 bg-white rounded-2xl shadow-sm border border-slate-100 hover:border-purple-500 hover:shadow-lg hover:-translate-y-0.5 transition-all text-center">
                <div class="h-12 w-12 mx-auto rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition-colors shadow-sm">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block">Surat Terbit &amp; TTE</span>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Arsip Dokumen PDF</span>
            </a>

            <a href="{{ route('portal.profil') }}" class="group p-5 bg-white rounded-2xl shadow-sm border border-slate-100 hover:border-amber-500 hover:shadow-lg hover:-translate-y-0.5 transition-all text-center">
                <div class="h-12 w-12 mx-auto rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mb-3 group-hover:bg-amber-600 group-hover:text-white transition-colors shadow-sm">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block">Profil Saya</span>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Data Kependudukan SIAK</span>
            </a>
        </div>
    </div>

    <!-- Recent Submissions Table -->
    <div class="mt-10 overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100">
        <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Pengajuan Terbaru Anda</h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar permohonan surat yang baru saja Anda ajukan.</p>
            </div>
            <a href="{{ route('portal.pengajuan.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-800">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No. Referensi</th>
                        <th class="px-6 py-4">Jenis Surat</th>
                        <th class="px-6 py-4">Tanggal Pengajuan</th>
                        <th class="px-6 py-4">Status Progress</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pengajuan as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-800">{{ $item->nomor_referensi }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->jenisSurat?->nama }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500 font-medium">{{ $item->tanggal_pengajuan?->format('d M Y, H:i') }} WIB</td>
                            <td class="px-6 py-4">
                                @php($warna = $item->status->color())
                                <span @class([
                                    'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold shadow-sm',
                                    'bg-slate-100 text-slate-700 border border-slate-200' => $warna === 'gray',
                                    'bg-blue-50 text-blue-700 border border-blue-200' => $warna === 'info',
                                    'bg-amber-50 text-amber-700 border border-amber-200' => $warna === 'warning',
                                    'bg-rose-50 text-rose-700 border border-rose-200' => $warna === 'danger',
                                    'bg-emerald-50 text-emerald-700 border border-emerald-200' => $warna === 'success',
                                ])>
                                    <span @class([
                                        'h-1.5 w-1.5 rounded-full',
                                        'bg-slate-500' => $warna === 'gray',
                                        'bg-blue-500' => $warna === 'info',
                                        'bg-amber-500 animate-pulse' => $warna === 'warning',
                                        'bg-rose-500' => $warna === 'danger',
                                        'bg-emerald-500' => $warna === 'success',
                                    ])></span>
                                    <span>{{ $item->status->label() }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('portal.pengajuan.status', $item->id) }}"
                                    class="inline-flex items-center gap-1 rounded-xl bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                    <span>Detail / Lacak</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="h-16 w-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Belum Ada Pengajuan Surat</p>
                                <p class="text-xs text-slate-400 mt-1">Anda belum pernah membuat pengajuan surat pada portal ini.</p>
                                <a href="{{ route('portal.pengajuan.buat') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-sm">
                                    + Buat Pengajuan Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
