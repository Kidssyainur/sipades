<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nomor_surat_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('nomor_terakhir')->default(0);
            $table->timestamps();

            $table->unique(['jenis_surat_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nomor_surat_counters');
    }
};
