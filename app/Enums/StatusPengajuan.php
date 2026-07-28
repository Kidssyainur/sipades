<?php

namespace App\Enums;

enum StatusPengajuan: string
{
    case DIAJUKAN = 'diajukan';
    case DIVERIFIKASI_PETUGAS = 'diverifikasi_petugas';
    case DIREVISI = 'direvisi';
    case DITOLAK = 'ditolak';
    case DISETUJUI_SEKRETARIS = 'disetujui_sekretaris';
    case DISETUJUI_KEPALA = 'disetujui_kepala';
    case SELESAI = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::DIAJUKAN => 'Diajukan',
            self::DIVERIFIKASI_PETUGAS => 'Diverifikasi Petugas',
            self::DIREVISI => 'Perlu Revisi',
            self::DITOLAK => 'Ditolak',
            self::DISETUJUI_SEKRETARIS => 'Disetujui Sekretaris Desa',
            self::DISETUJUI_KEPALA => 'Disetujui Kepala Desa',
            self::SELESAI => 'Selesai (Surat Terbit)',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DIAJUKAN => 'gray',
            self::DIVERIFIKASI_PETUGAS => 'info',
            self::DIREVISI => 'warning',
            self::DITOLAK => 'danger',
            self::DISETUJUI_SEKRETARIS => 'info',
            self::DISETUJUI_KEPALA => 'success',
            self::SELESAI => 'success',
        };
    }
}
