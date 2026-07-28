@extends('surat.partials.layout', ['judul' => 'Surat Keterangan Kelahiran'])

@section('isi')
    <p>Yang bertanda tangan di bawah ini, Kepala Desa {{ $desa['nama'] }}, Kecamatan {{ $desa['kecamatan'] }},
        Kabupaten {{ $desa['kabupaten'] }}, dengan ini menerangkan bahwa telah lahir seorang anak:</p>

    <table class="identitas">
        <tr><td class="label">Nama Anak</td><td class="sep">:</td><td>{{ $data['nama_anak'] ?? '-' }}</td></tr>
        <tr><td class="label">Jenis Kelamin</td><td class="sep">:</td><td>{{ $data['jenis_kelamin'] ?? '-' }}</td></tr>
        <tr><td class="label">Tempat Lahir</td><td class="sep">:</td><td>{{ $data['tempat_lahir'] ?? '-' }}</td></tr>
        <tr><td class="label">Tanggal Lahir</td><td class="sep">:</td><td>{{ ! empty($data['tanggal_lahir']) ? \Illuminate\Support\Carbon::parse($data['tanggal_lahir'])->translatedFormat('d F Y') : '-' }}</td></tr>
        <tr><td class="label">Nama Ayah</td><td class="sep">:</td><td>{{ $data['nama_ayah'] ?? '-' }}</td></tr>
        <tr><td class="label">Nama Ibu</td><td class="sep">:</td><td>{{ $data['nama_ibu'] ?? '-' }}</td></tr>
    </table>

    <p style="margin-top:12px;">Yang dilaporkan oleh:</p>
    @include('surat.partials.identitas')

    <p style="margin-top:12px;">Surat keterangan kelahiran ini dibuat untuk keperluan pengurusan akta kelahiran
        dan administrasi kependudukan lainnya.</p>

    <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
@endsection
