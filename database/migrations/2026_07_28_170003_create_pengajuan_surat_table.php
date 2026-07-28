<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_referensi', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat');
            $table->json('data_formulir');
            $table->string('status', 30)->default('diajukan');
            // diajukan | diverifikasi_petugas | direvisi | ditolak
            // disetujui_sekretaris | disetujui_kepala | selesai
            $table->unsignedTinyInteger('current_level')->default(1); // 1=Petugas 2=Sekretaris 3=KepalaDesa
            $table->text('catatan_revisi')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('tanggal_pengajuan');
            $table->timestamp('tanggal_selesai')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'current_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
