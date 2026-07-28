<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_pesan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 40)->unique();
            // ex: PENGAJUAN_DITERIMA, REVISI_DIMINTA, DITOLAK,
            //     DISETUJUI_PETUGAS, DISETUJUI_SEKRETARIS, SURAT_TERBIT
            $table->string('judul');
            $table->text('isi_template'); // placeholder: {nama}, {nomor_referensi}, {jenis_surat}, {catatan}
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_pesan');
    }
};
