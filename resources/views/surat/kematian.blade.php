@extends('surat.partials.layout', ['judul' => 'Surat Keterangan Kematian'])

@section('isi')
    <p>Yang bertanda tangan di bawah ini, Kepala Desa {{ $desa['nama'] }}, Kecamatan {{ $desa['kecamatan'] }},
        Kabupaten {{ $desa['kabupaten'] }}, dengan ini menerangkan bahwa telah meninggal dunia:</p>

    <table class="identitas">
        <tr><td class="label">Nama Almarhum/ah</td><td class="sep">:</td><td>{{ $data['nama_almarhum'] ?? '-' }}</td></tr>
        <tr><td class="label">Tanggal Meninggal</td><td class="sep">:</td><td>{{ ! empty($data['tanggal_meninggal']) ? \Illuminate\Support\Carbon::parse($data['tanggal_meninggal'])->translatedFormat('d F Y') : '-' }}</td></tr>
        <tr><td class="label">Tempat Meninggal</td><td class="sep">:</td><td>{{ $data['tempat_meninggal'] ?? '-' }}</td></tr>
        @if (! empty($data['sebab_meninggal']))
            <tr><td class="label">Sebab Meninggal</td><td class="sep">:</td><td>{{ $data['sebab_meninggal'] }}</td></tr>
        @endif
    </table>

    <p style="margin-top:12px;">Yang dilaporkan oleh:</p>
    @include('surat.partials.identitas')

    <p style="margin-top:12px;">Surat keterangan kematian ini dibuat untuk keperluan pengurusan akta kematian
        dan administrasi kependudukan lainnya.</p>

    <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
@endsection
