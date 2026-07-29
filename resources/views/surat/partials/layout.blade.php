{{--
    Layout dasar seluruh surat resmi.
    Slot: $judul, $nomor (opsional override), $isi (HTML tubuh surat).
    Variabel global: $pengajuan, $penduduk, $data, $nomorSurat, $tanggalTerbit, $desa
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $judul ?? 'Surat Resmi' }} - {{ $nomorSurat }}</title>
    <style>
        @page { margin: 2.2cm 2.4cm; }
        * { box-sizing: border-box; }
        body {
            font-family: "DejaVu Sans", "Times New Roman", serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.5;
        }
        .kop {
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 4px;
        }
        .kop table { width: 100%; border-collapse: collapse; }
        .kop .logo { width: 90px; text-align: center; vertical-align: middle; }
        .kop .logo .placeholder {
            width: 74px; height: 74px; border: 1px solid #555; border-radius: 50%;
            display: inline-block; line-height: 74px; font-size: 7pt; color: #777;
        }
        .kop .teks { text-align: center; vertical-align: middle; }
        .kop .teks .baris1 { font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .kop .teks .baris2 { font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .kop .teks .baris3 { font-size: 18pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .kop .teks .alamat { font-size: 9pt; margin-top: 2px; }
        .garis-tipis { border-bottom: 1px solid #000; margin-bottom: 18px; }

        .judul-surat { text-align: center; margin: 18px 0 4px; }
        .judul-surat h2 {
            font-size: 13pt; font-weight: bold; text-transform: uppercase;
            text-decoration: underline; margin: 0;
        }
        .judul-surat .nomor { font-size: 11pt; margin-top: 2px; }

        .isi { text-align: justify; margin-top: 14px; }
        .isi p { margin: 0 0 10px; }

        table.identitas { border-collapse: collapse; margin: 6px 0 6px 24px; }
        table.identitas td { vertical-align: top; padding: 1px 0; }
        table.identitas td.label { width: 190px; }
        table.identitas td.sep { width: 14px; }

        .ttd { width: 100%; margin-top: 36px; }
        .ttd table { width: 100%; border-collapse: collapse; }
        .ttd .kolom-kanan { width: 45%; text-align: center; vertical-align: top; }
        .ttd .spasi-ttd { height: 78px; }
        .ttd .nama-pejabat { font-weight: bold; text-decoration: underline; }

        .footer-catatan { margin-top: 40px; font-size: 8.5pt; color: #444; border-top: 1px solid #999; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="kop">
        <table>
            <tr>
                <td class="logo">
                    <span class="placeholder">LOGO</span>
                </td>
                <td class="teks">
                    <div class="baris1">Pemerintah Kabupaten {{ $desa['kabupaten'] }}</div>
                    <div class="baris2">Kecamatan {{ $desa['kecamatan'] }}</div>
                    <div class="baris3">{{ $desa['nama'] }}</div>
                    <div class="alamat">Alamat: Jl. Raya {{ $desa['nama'] }}, Kec. {{ $desa['kecamatan'] }}, Kab. {{ $desa['kabupaten'] }}</div>
                </td>
                <td class="logo"></td>
            </tr>
        </table>
    </div>
    <div class="garis-tipis"></div>

    <div class="judul-surat">
        <h2>@yield('judul', $judul ?? 'Surat Keterangan')</h2>
        <div class="nomor">Nomor: {{ $nomorSurat }}</div>
    </div>

    <div class="isi">
        @hasSection('isi')
            @yield('isi')
        @else
            {!! $isi ?? '' !!}
        @endif
    </div>

    <div class="ttd">
        @include('surat.partials.footer_tte')
    </div>

    <div class="footer-catatan">
        Dokumen ini diterbitkan melalui Sistem Informasi Pelayanan Desa {{ $desa['nama'] }}.
        Nomor referensi pengajuan: {{ $pengajuan->nomor_referensi }}.
    </div>
</body>
</html>
