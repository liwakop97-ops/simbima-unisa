<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->cascadeOnDelete();
            $table->string('file_laporan_kemajuan');
            $table->string('file_sptb_70')->nullable();
            $table->unsignedTinyInteger('persentase_kemajuan')->default(70);
            $table->text('ringkasan_kemajuan');
            $table->enum('status_monev', ['submitted', 'needs_revision', 'approved'])->default('submitted');
            $table->text('catatan_monev')->nullable();
            $table->decimal('skor_monev', 5, 2)->nullable();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_reports');
    }
};
