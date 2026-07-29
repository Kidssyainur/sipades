<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ids = [
    'pengajuan_id' => \App\Models\PengajuanSurat::first()?->id,
    'jenis_surat_id' => \App\Models\JenisSurat::first()?->id,
    'penduduk_id' => \App\Models\DataKependudukan::first()?->id,
    'surat_terbit_id' => \App\Models\SuratTerbit::first()?->id,
    'template_pesan_id' => \App\Models\TemplatePesan::first()?->id,
    'user_id' => \App\Models\User::first()?->id,
];

echo json_encode($ids, JSON_PRETTY_PRINT);
