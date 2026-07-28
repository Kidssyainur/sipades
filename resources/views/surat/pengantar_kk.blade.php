@extends('surat.partials.layout', ['judul' => 'Surat Pengantar Kartu Keluarga'])

@section('isi')
    <p>Yang bertanda tangan di bawah ini, Kepala Desa {{ $desa['nama'] }}, Kecamatan {{ $desa['kecamatan'] }},
        Kabupaten {{ $desa['kabupaten'] }}, dengan ini memberikan pengantar kepada:</p>

    @include('surat.partials.identitas')

    <p style="margin-top:12px;">Untuk mengurus <strong>Kartu Keluarga (KK)</strong> pada Dinas Kependudukan
        dan Pencatatan Sipil Kabupaten {{ $desa['kabupaten'] }}, dengan jenis permohonan:</p>
    <table class="identitas">
        <tr><td class="label">Jenis Permohonan</td><td class="sep">:</td><td>{{ $data['jenis_permohonan'] ?? '-' }}</td></tr>
    </table>

    <p style="margin-top:12px;">Demikian surat pengantar ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
@endsection
