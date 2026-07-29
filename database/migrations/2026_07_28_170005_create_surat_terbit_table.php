<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_terbit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->unique()->constrained('pengajuan_surat');
            $table->string('nomor_surat', 60)->unique();
            $table->foreignId('diterbitkan_oleh')->constrained('users'); // Kepala Desa
            $table->string('file_path');
            $table->string('tte_token', 64)->nullable()->unique();
            $table->string('qr_code_path')->nullable();
            $table->timestamp('tanggal_terbit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_terbit');
    }
};
