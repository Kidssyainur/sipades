<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('kategori', 60)->default('Berita Desa');
            $table->date('tanggal');
            $table->string('penulis', 100)->nullable(); // nama penulis/redaksi
            $table->string('gambar')->nullable();       // path gambar sampul di disk public
            $table->text('ringkasan')->nullable();      // cuplikan singkat untuk kartu landing
            $table->longText('isi');                    // HTML hasil RichEditor
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index('status');
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
