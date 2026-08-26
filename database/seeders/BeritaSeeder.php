<?php

namespace Database\Seeders;

use App\Models\Berita;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $beritaConfig = config('landing.berita', []);

        foreach ($beritaConfig as $item) {
            $judul = $item['judul'];
            // Pertahankan slug berita yang sudah ada agar seeder tetap idempoten;
            // hanya berita baru yang diberi slug unik.
            $existing = Berita::where('judul', $judul)->first();
            $slug = $existing?->slug ?? Berita::buatSlugUnik($judul);

            Berita::updateOrCreate(
                ['slug' => $slug],
                [
                    'judul' => $judul,
                    'kategori' => self::tebakKategori($judul),
                    'tanggal' => self::parseTanggal($item['tanggal']),
                    'penulis' => 'Admin Desa',
                    'ringkasan' => $item['isi'],
                    'isi' => '<p>'.$item['isi'].'</p>',
                    'status' => 'published',
                    'is_featured' => false,
                    'views' => 0,
                ],
            );
        }
    }

    /** Terima format "31 Juli 2026", "2025", "2018", dst. */
    private static function parseTanggal(string $nilai): Carbon
    {
        Carbon::setLocale('id');
        $nilai = trim($nilai);

        // Hanya tahun (mis. "2025")
        if (preg_match('/^\d{4}$/', $nilai)) {
            return Carbon::createFromFormat('Y-m-d', $nilai.'-01-01');
        }

        try {
            return Carbon::parse($nilai);
        } catch (\Throwable $e) {
            return Carbon::now();
        }
    }

    private static function tebakKategori(string $judul): string
    {
        $judul = Str::lower($judul);

        return match (true) {
            Str::contains($judul, ['juara', 'lomba', 'prestasi', 'menang']) => 'Prestasi',
            Str::contains($judul, ['wisata', 'ukir', 'pameran']) => 'Wisata',
            Str::contains($judul, ['dana desa', 'pemkab', 'bantuan', 'pemulihan']) => 'Pemerintahan',
            default => 'Berita Desa',
        };
    }
}
