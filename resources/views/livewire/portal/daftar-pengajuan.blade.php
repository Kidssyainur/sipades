<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengajuan Saya</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola dan pantau seluruh riwayat permohonan surat Anda.</p>
        </div>
        <a href="{{ route('portal.pengajuan.buat') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Buat Pengajuan Baru
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-between bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex-1 min-w-[240px]">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor referensi / jenis surat..."
                class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
        </div>
        <div class="w-full sm:w-64">
            <select wire:model.live="statusFilter" class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua Status Status</option>
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

    <!-- Data Table -->
    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm border border-gray-200/80">
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
                    <tr class="hover:bg-gray-50/70 transition">
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
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('portal.pengajuan.status', $item->id) }}"
                                class="inline-flex items-center gap-1 font-semibold text-emerald-600 hover:text-emerald-800">
                                Detail / Lacak
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Tidak ada pengajuan surat ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pengajuan->links() }}
    </div>
</div>
