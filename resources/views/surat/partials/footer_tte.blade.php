<table style="width: 100%; margin-top: 25px; border-collapse: collapse;">
    <tr>
        <td style="width: 55%; vertical-align: top; text-align: left; font-size: 9pt;">
            @php
                $activeToken = $tteToken ?? $suratTerbit?->tte_token ?? null;
            @endphp
            @if(!empty($activeToken))
                <div style="border: 1px solid #10b981; background-color: #f0fdf4; padding: 8px 10px; border-radius: 6px; width: 90%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 75px; text-align: center; vertical-align: middle;">
                                @if(!empty($qrBase64))
                                    <img src="{{ $qrBase64 }}" alt="QR TTE" style="width: 70px; height: 70px; display: block;" />
                                @endif
                            </td>
                            <td style="vertical-align: middle; font-size: 8pt; color: #065f46; padding-left: 8px;">
                                <strong style="font-size: 8.5pt; color: #047857;">TTE RESMI TERVERIFIKASI</strong><br/>
                                Dokumen ini disahkan secara elektronik.<br/>
                                <span>TTE ID: {{ substr($activeToken, 0, 18) }}...</span><br/>
                                <span style="font-size: 7.5pt; color: #047857; font-weight: bold;">Pemerintah Desa Karduluk</span>
                            </td>
                        </tr>
                    </table>
                </div>
            @endif
        </td>
        <td style="width: 45%; vertical-align: top; text-align: center; font-size: 11pt;">
            Karduluk, {{ isset($tanggalTerbit) ? \Carbon\Carbon::parse($tanggalTerbit)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}<br/>
            <strong>
                @if(isset($pengajuan->jenisSurat) && $pengajuan->jenisSurat->jumlah_level_approval == 1)
                    An. Kepala Desa Karduluk<br/>Petugas Pelayanan Desa
                @else
                    Kepala Desa Karduluk
                @endif
            </strong>
            <br/><br/><br/><br/>
            <u><strong>{{ $penandatangan['nama'] ?? 'AHMAD ZAENI' }}</strong></u>
        </td>
    </tr>
</table>
