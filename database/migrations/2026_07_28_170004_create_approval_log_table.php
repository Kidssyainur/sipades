<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained('pengajuan_surat')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // approver
            $table->unsignedTinyInteger('level'); // 1, 2, 3
            $table->string('role_saat_itu', 30); // snapshot role, jaga histori jika role user berubah nanti
            $table->enum('keputusan', ['setuju', 'revisi', 'tolak']);
            $table->text('catatan')->nullable();
            $table->timestamp('ditandatangani_pada')->nullable(); // diisi khusus level 3 (Kepala Desa)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_log');
    }
};
