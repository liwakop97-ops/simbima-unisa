<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('final_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->cascadeOnDelete();
            $table->string('file_laporan_akhir');
            $table->string('file_sptb_100')->nullable();
            $table->text('ringkasan_hasil');
            $table->enum('status_akhir', ['submitted', 'needs_revision', 'verified'])->default('submitted');
            $table->text('catatan_akhir')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_reports');
    }
};
