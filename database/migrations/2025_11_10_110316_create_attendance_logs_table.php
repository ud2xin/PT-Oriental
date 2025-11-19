<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users (supaya bisa pakai ->with('user'))
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            // Data absensi dari mesin fingerprint
            $table->timestamp('tanggal_scan')->nullable(); // Tanggal scan lengkap (tanggal + jam)
            $table->date('tanggal')->nullable();           // Tanggal absensi
            $table->time('jam')->nullable();               // Jam absensi
            $table->string('pin', 20)->nullable();         // PIN dari mesin
            $table->string('nip', 20)->nullable();         // NIP pegawai
            $table->string('nama', 100)->nullable();       // Nama pegawai
            $table->string('jabatan', 100)->nullable();    // Jabatan
            $table->string('departemen', 100)->nullable(); // Departemen
            $table->string('kantor', 100)->nullable();     // Kantor
            $table->integer('verifikasi')->nullable();     // Kode verifikasi
            $table->tinyInteger('io')->nullable();         // 1=Masuk, 2=Keluar
            $table->integer('workcode')->nullable();       // Kode kerja
            $table->string('sn', 50)->nullable();          // Nomor seri mesin
            $table->string('mesin', 50)->nullable();       // Nama mesin

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
