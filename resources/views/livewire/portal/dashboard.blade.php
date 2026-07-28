<div>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Halo, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">Ringkasan pengajuan surat Anda.</p>
        </div>
        <a href="{{ route('portal.pengajuan.buat') }}" wire:navigate
            class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
            + Ajukan Surat
        </a>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <p class="text-sm text-gray-500">Total Pengajuan</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $ringkasan['total'] }}</p>
        </div>
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <p class="text-sm text-gray-500">Sedang Diproses</p>
            <p class="mt-1 text-3xl font-bold text-amber-600">{{ $ringkasan['proses'] }}</p>
        </div>
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <p class="text-sm text-gray-500">Selesai</p>
            <p class="mt-1 text-3xl font-bold text-emerald-600">{{ $ringkasan['selesai'] }}</p>
        </div>
    </div>

    <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">No. Referensi</th>
                    <th class="px-4 py-3">Jenis Surat</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pengajuan as $item)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $item->nomor_referensi }}</td>
                        <td class="px-4 py-3">{{ $item->jenisSurat?->nama }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $item->tanggal_pengajuan?->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            @php($warna = $item->status->color())
                            <span @class([
                                'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                'bg-gray-100 text-gray-700' => $warna === 'gray',
                                'bg-blue-100 text-blue-700' => $warna === 'info',
                                'bg-amber-100 text-amber-700' => $warna === 'warning',
                                'bg-red-100 text-red-700' => $warna === 'danger',
                                'bg-emerald-100 text-emerald-700' => $warna === 'success',
                            ])>
                                {{ $item->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('portal.pengajuan.status', $item->id) }}" wire:navigate
                                class="font-medium text-emerald-600 hover:underline">Lacak</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                            Belum ada pengajuan. Klik "Ajukan Surat" untuk memulai.
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
