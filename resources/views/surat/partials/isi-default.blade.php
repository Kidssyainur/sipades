{{-- Tubuh surat generik: identitas + dump seluruh isian formulir. --}}
<p>Yang bertanda tangan di bawah ini, Kepala Desa {{ $desa['nama'] ?? '' }}, dengan ini menerangkan bahwa:</p>

@include('surat.partials.identitas')

<p style="margin-top:12px;">Adalah benar warga Desa {{ $desa['nama'] ?? '' }} dan mengajukan
    <strong>{{ $pengajuan->jenisSurat->nama ?? 'surat keterangan' }}</strong> dengan rincian sebagai berikut:</p>

@if (! empty($data))
    <table class="identitas">
        @foreach ($data as $key => $value)
            <tr>
                <td class="label">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $key)) }}</td>
                <td class="sep">:</td>
                <td>{{ is_array($value) ? implode(', ', $value) : $value }}</td>
            </tr>
        @endforeach
    </table>
@endif

<p style="margin-top:12px;">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
