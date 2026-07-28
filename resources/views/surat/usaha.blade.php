@extends('surat.partials.layout', ['judul' => 'Surat Keterangan Usaha'])

@section('isi')
    <p>Yang bertanda tangan di bawah ini, Kepala Desa {{ $desa['nama'] }}, Kecamatan {{ $desa['kecamatan'] }},
        Kabupaten {{ $desa['kabupaten'] }}, dengan ini menerangkan bahwa:</p>

    @include('surat.partials.identitas')

    <p style="margin-top:12px;">Nama tersebut di atas adalah benar warga Desa {{ $desa['nama'] }} yang memiliki usaha
        dengan rincian sebagai berikut:</p>
    <table class="identitas">
        <tr><td class="label">Nama Usaha</td><td class="sep">:</td><td>{{ $data['nama_usaha'] ?? '-' }}</td></tr>
        <tr><td class="label">Jenis Usaha</td><td class="sep">:</td><td>{{ $data['jenis_usaha'] ?? '-' }}</td></tr>
        <tr><td class="label">Alamat Usaha</td><td class="sep">:</td><td>{{ $data['alamat_usaha'] ?? '-' }}</td></tr>
        @if (! empty($data['tahun_berdiri']))
            <tr><td class="label">Tahun Berdiri</td><td class="sep">:</td><td>{{ $data['tahun_berdiri'] }}</td></tr>
        @endif
    </table>

    <p style="margin-top:12px;">Surat keterangan usaha ini dibuat untuk keperluan pengurusan administrasi
        dan kelengkapan berkas yang diperlukan.</p>

    <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
@endsection
