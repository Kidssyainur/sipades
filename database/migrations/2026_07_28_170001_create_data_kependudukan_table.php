<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_kependudukan', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('rt_rw', 10)->nullable();
            $table->string('agama', 30)->nullable();
            $table->string('status_perkawinan', 30)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('kewarganegaraan', 5)->default('WNI');
            $table->boolean('sudah_didaftarkan')->default(false); // cegah 1 NIK dipakai >1 akun
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_kependudukan');
    }
};
