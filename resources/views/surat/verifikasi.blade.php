<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi TTE Surat Resmi - Desa Karduluk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-gray-900">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-xl">
            <div class="text-center">
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 mb-3">
                    ✓ Dokumen Resmi Terverifikasi
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Pemerintah Desa Karduluk</h1>
                <p class="mt-1 text-sm text-gray-600">Sistem Informasi Pelayanan Desa (SIPADES)</p>
            </div>

            <div class="mt-6 bg-white py-8 px-6 shadow-xl rounded-2xl sm:px-10 border border-emerald-100">
                <div class="border-b border-gray-100 pb-5 mb-5 flex items-center gap-4">
                    <div class="rounded-full bg-emerald-500 p-3 text-white">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-emerald-900">Keabsahan TTE Terkonfirmasi</h2>
                        <p class="text-xs text-gray-500">Dokumen ini secara sah dikeluarkan dan ditandatangani secara elektronik.</p>
                    </div>
                </div>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Jenis Surat:</span>
                        <span class="font-semibold text-gray-900">{{ $jenis?->nama }}</span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Nomor Surat Resmi:</span>
                        <span class="font-mono font-bold text-emerald-700">{{ $surat->nomor_surat }}</span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Nama Pemohon:</span>
                        <span class="font-medium text-gray-900">{{ $warga?->name }}</span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">NIK Pemohon:</span>
                        <span class="font-mono text-gray-800">
                            {{ $warga?->nik ? substr($warga->nik, 0, 6) . '******' . substr($warga->nik, -4) : '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Tanggal Terbit:</span>
                        <span class="text-gray-900">{{ $surat->tanggal_terbit?->format('d F Y H:i') }} WIB</span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Pengesah / TTE:</span>
                        <span class="font-semibold text-gray-900">{{ $penerbit?->name ?? 'Kepala Desa Karduluk' }}</span>
                    </div>

                    <div class="py-3 bg-gray-50 rounded-lg px-4 mt-4">
                        <span class="text-xs text-gray-500 block mb-1">TOKEN KEABSAHAN TTE (SHA-256):</span>
                        <span class="font-mono text-xs break-all font-semibold text-emerald-800">{{ $surat->tte_token }}</span>
                    </div>
                </div>

                <div class="mt-6 text-center text-xs text-gray-400">
                    Sistem Informasi Pelayanan Desa Karduluk - Kabupaten Sumenep
                </div>
            </div>
        </div>
    </div>
</body>
</html>
