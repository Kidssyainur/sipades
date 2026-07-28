<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique(); // ex: SK_DOMISILI, SKTM, SK_USAHA
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->json('persyaratan')->nullable();     // ["KTP", "KK", "Surat Pengantar RT"]
            $table->json('field_formulir')->nullable();  // definisi field dinamis formulir
            $table->string('template_view', 100)->nullable(); // nama blade template surat, ex: 'surat.domisili'
            $table->unsignedTinyInteger('estimasi_hari')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_surat');
    }
};
