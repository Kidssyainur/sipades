@extends('surat.partials.layout', ['judul' => 'Surat Keterangan Tidak Mampu'])

@section('isi')
    <p>Yang bertanda tangan di bawah ini, Kepala Desa {{ $desa['nama'] }}, Kecamatan {{ $desa['kecamatan'] }},
        Kabupaten {{ $desa['kabupaten'] }}, dengan ini menerangkan bahwa:</p>

    @include('surat.partials.identitas')

    <p style="margin-top:12px;">Berdasarkan pengamatan dan keterangan yang dapat dipercaya, nama tersebut di atas
        adalah benar warga Desa {{ $desa['nama'] }} yang tergolong keluarga
        <strong>kurang mampu / tidak mampu</strong> secara ekonomi.</p>

    @if (! empty($data['penghasilan']))
        <table class="identitas">
            <tr><td class="label">Penghasilan per Bulan</td><td class="sep">:</td><td>Rp {{ number_format((float) $data['penghasilan'], 0, ',', '.') }}</td></tr>
        </table>
    @endif

    <p style="margin-top:12px;">Surat keterangan tidak mampu ini dibuat untuk keperluan
        <strong>{{ $data['keperluan'] ?? '-' }}</strong>.</p>

    <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
@endsection
