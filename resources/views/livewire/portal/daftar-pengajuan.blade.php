<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Pengajuan Saya</h1>
            <p class="mt-1 text-xs text-slate-500">Kelola dan pantau seluruh riwayat permohonan surat Anda secara langsung.</p>
        </div>
        <a href="{{ route('portal.pengajuan.buat') }}"
            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 px-5 py-3 text-xs font-bold text-white shadow-lg shadow-emerald-950/20 hover:from-emerald-400 hover:to-teal-400 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>+ Buat Pengajuan Baru</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex-1 min-w-[240px]">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor referensi / jenis surat..."
                    class="w-full rounded-xl border-slate-200 bg-slate-50/50 pl-9 pr-4 py-2.5 text-xs focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm transition-all" />
            </div>
        </div>
        <div class="w-full sm:w-64">
            <select wire:model.live="statusFilter" class="w-full rounded-xl border-slate-200 bg-slate-50/50 py-2.5 text-xs focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm font-semibold text-slate-700">
                <option value="">Semua Status Pengajuan</option>
                <option value="diajukan">Diajukan</option>
                <option value="diverifikasi_petugas">Diverifikasi Petugas</option>
                <option value="disetujui_sekretaris">Disetujui Sekdes</option>
                <option value="disetujui_kepala">Disetujui Kades</option>
                <option value="selesai">Selesai (Terbit)</option>
                <option value="direvisi">Perlu Revisi</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100">
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
                                <p class="text-sm font-bold text-slate-700">Pengajuan Tidak Ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter status Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $pengajuan->links() }}
    </div>
</div>
