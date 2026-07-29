<div>
    <!-- Welcome Header Banner -->
    <div class="rounded-2xl bg-gradient-to-r from-emerald-800 to-teal-700 p-6 text-white shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <span class="inline-block rounded-md bg-emerald-700/60 px-3 py-1 text-xs font-semibold text-emerald-200 mb-2 border border-emerald-600/50">
                Portal Resmi Warga Desa Karduluk
            </span>
            <h1 class="text-2xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-xs text-emerald-100 flex flex-wrap gap-4 font-mono">
                <span>NIK: {{ auth()->user()->nik ?? '-' }}</span>
                <span>•</span>
                <span>No. HP WhatsApp: {{ auth()->user()->no_hp ?? '-' }}</span>
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('portal.pengajuan.buat') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-emerald-900 shadow-sm hover:bg-emerald-50 transition">
                <svg class="w-5 h-5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Buat Pengajuan Surat
            </a>
            <a href="{{ route('portal.profil') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-900/60 border border-emerald-600 px-4 py-3 text-sm font-semibold text-emerald-100 hover:bg-emerald-900 transition">
                Profil Saya
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <a href="{{ route('portal.pengajuan.index') }}" class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-500 group-hover:text-emerald-700">Total Pengajuan</span>
                <span class="rounded-xl bg-gray-100 p-2.5 text-gray-600 group-hover:bg-emerald-50 group-hover:text-emerald-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
            </div>
            <p class="mt-3 text-3xl font-bold text-gray-900">{{ $ringkasan['total'] }}</p>
        </a>

        <a href="{{ route('portal.pengajuan.index') }}?statusFilter=diajukan" class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-500 group-hover:text-amber-700">Dalam Proses Approval</span>
                <span class="rounded-xl bg-amber-50 p-2.5 text-amber-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="mt-3 text-3xl font-bold text-amber-600">{{ $ringkasan['proses'] }}</p>
        </a>

        <a href="{{ route('portal.surat-terbit.index') }}" class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-500 group-hover:text-emerald-700">Surat Terbit & TTE</span>
                <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
            </div>
            <p class="mt-3 text-3xl font-bold text-emerald-600">{{ $ringkasan['selesai'] }}</p>
        </a>
    </div>

    <!-- Quick Shortcuts Navigation Grid -->
    <div class="mt-8">
        <h2 class="text-base font-bold text-gray-900 mb-4">Layanan Cepat Portal Warga</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('portal.pengajuan.buat') }}" class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-emerald-500 hover:shadow transition text-center group">
                <div class="h-10 w-10 mx-auto rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center mb-2 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-800 block">Ajukan Surat</span>
            </a>

            <a href="{{ route('portal.pengajuan.index') }}" class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-emerald-500 hover:shadow transition text-center group">
                <div class="h-10 w-10 mx-auto rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center mb-2 group-hover:bg-blue-600 group-hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-800 block">Pengajuan Saya</span>
            </a>

            <a href="{{ route('portal.surat-terbit.index') }}" class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-emerald-500 hover:shadow transition text-center group">
                <div class="h-10 w-10 mx-auto rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center mb-2 group-hover:bg-purple-600 group-hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-800 block">Surat Terbit</span>
            </a>

            <a href="{{ route('portal.profil') }}" class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-emerald-500 hover:shadow transition text-center group">
                <div class="h-10 w-10 mx-auto rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center mb-2 group-hover:bg-amber-600 group-hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-800 block">Profil Saya</span>
            </a>
        </div>
    </div>

    <!-- Recent Submissions Table -->
    <div class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 flex items-center justify-between">
            <h2 class="text-base font-bold text-gray-900">Pengajuan Terbaru Anda</h2>
            <a href="{{ route('portal.pengajuan.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">Lihat Semua →</a>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-6 py-3.5">No. Referensi</th>
                    <th class="px-6 py-3.5">Jenis Surat</th>
                    <th class="px-6 py-3.5">Tanggal Pengajuan</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pengajuan as $item)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-800">{{ $item->nomor_referensi }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $item->jenisSurat?->nama }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $item->tanggal_pengajuan?->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4">
                            @php($warna = $item->status->color())
                            <span @class([
                                'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold',
                                'bg-gray-100 text-gray-700' => $warna === 'gray',
                                'bg-blue-100 text-blue-700' => $warna === 'info',
                                'bg-amber-100 text-amber-700' => $warna === 'warning',
                                'bg-red-100 text-red-700' => $warna === 'danger',
                                'bg-emerald-100 text-emerald-700' => $warna === 'success',
                            ])>
                                {{ $item->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('portal.pengajuan.status', $item->id) }}"
                                class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-800">
                                <span>Detail / Lacak</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Belum ada pengajuan surat. Klik tombol "+ Buat Pengajuan Surat" di atas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
