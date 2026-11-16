<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grant_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('kode_skema', 20)->unique();
            $table->string('nama_skema');
            $table->enum('kategori', ['penelitian', 'pengabdian']);
            $table->year('tahun_anggaran');
            $table->decimal('plafon_maksimal', 15, 2);
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grant_schemes');
    }
};
