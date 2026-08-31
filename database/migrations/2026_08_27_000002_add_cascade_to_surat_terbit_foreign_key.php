<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_terbit', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_surat_id']);
            $table->foreign('pengajuan_surat_id')
                ->references('id')
                ->on('pengajuan_surat')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('surat_terbit', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_surat_id']);
            $table->foreign('pengajuan_surat_id')
                ->references('id')
                ->on('pengajuan_surat');
        });
    }
};
