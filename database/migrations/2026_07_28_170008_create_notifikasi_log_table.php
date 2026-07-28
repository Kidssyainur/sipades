<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('pengajuan_surat_id')->nullable()->constrained('pengajuan_surat')->nullOnDelete();
            $table->foreignId('template_pesan_id')->nullable()->constrained('template_pesan')->nullOnDelete();
            $table->string('no_hp_tujuan', 20);
            $table->text('pesan');
            $table->string('status', 20)->default('pending'); // pending | terkirim | gagal
            $table->unsignedTinyInteger('percobaan')->default(0);
            $table->text('response_gateway')->nullable();
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi_log');
    }
};
