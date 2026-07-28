@extends('surat.partials.layout', ['judul' => 'Surat Keterangan Domisili'])

@section('isi')
    <p>Yang bertanda tangan di bawah ini, Kepala Desa {{ $desa['nama'] }}, Kecamatan {{ $desa['kecamatan'] }},
        Kabupaten {{ $desa['kabupaten'] }}, dengan ini menerangkan bahwa:</p>

    @include('surat.partials.identitas')

    <p style="margin-top:12px;">Berdasarkan data yang ada, nama tersebut di atas adalah benar-benar berdomisili di:</p>
    <table class="identitas">
        <tr><td class="label">Alamat Domisili</td><td class="sep">:</td><td>{{ $data['alamat_domisili'] ?? ($penduduk->alamat ?? '-') }}</td></tr>
    </table>

    <p style="margin-top:12px;">Surat keterangan domisili ini dibuat untuk keperluan
        <strong>{{ $data['keperluan'] ?? '-' }}</strong>.</p>

    <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
@endsection
