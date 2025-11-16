<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Tambahkan kolom 'nip' setelah 'department_id'
            // 'nullable()' berarti boleh kosong, 'unique()' memastikan tidak ada NIP ganda
            $table->string('nip')->nullable()->unique()->after('department_id');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Untuk rollback jika terjadi kesalahan
            $table->dropColumn('nip');
        });
    }
};