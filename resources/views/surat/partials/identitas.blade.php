{{--
    Blok identitas pemohon, dipakai ulang lintas jenis surat.
    Mengutamakan data kependudukan ($penduduk); fallback ke akun warga.
--}}
@php
    $namaPemohon = $penduduk->nama ?? $pengajuan->warga->name ?? '-';
    $nikPemohon = $penduduk->nik ?? $pengajuan->warga->nik ?? '-';
    $ttl = $penduduk
        ? trim(($penduduk->tempat_lahir ?? '-').', '.optional($penduduk->tanggal_lahir)->translatedFormat('d F Y'))
        : '-';
@endphp
<table class="identitas">
    <tr><td class="label">Nama</td><td class="sep">:</td><td>{{ $namaPemohon }}</td></tr>
    <tr><td class="label">NIK</td><td class="sep">:</td><td>{{ $nikPemohon }}</td></tr>
    @if ($penduduk)
        <tr><td class="label">Tempat/Tanggal Lahir</td><td class="sep">:</td><td>{{ $ttl }}</td></tr>
        <tr><td class="label">Jenis Kelamin</td><td class="sep">:</td><td>{{ $penduduk->jenis_kelamin ?? '-' }}</td></tr>
        <tr><td class="label">Agama</td><td class="sep">:</td><td>{{ $penduduk->agama ?? '-' }}</td></tr>
        <tr><td class="label">Status Perkawinan</td><td class="sep">:</td><td>{{ $penduduk->status_perkawinan ?? '-' }}</td></tr>
        <tr><td class="label">Pekerjaan</td><td class="sep">:</td><td>{{ $penduduk->pekerjaan ?? '-' }}</td></tr>
        <tr><td class="label">Kewarganegaraan</td><td class="sep">:</td><td>{{ $penduduk->kewarganegaraan ?? 'Indonesia' }}</td></tr>
        <tr><td class="label">Alamat</td><td class="sep">:</td><td>{{ $penduduk->alamat ?? '-' }}{{ $penduduk->rt_rw ? ' RT/RW '.$penduduk->rt_rw : '' }}</td></tr>
    @endif
</table>
