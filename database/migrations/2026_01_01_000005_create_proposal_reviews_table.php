<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposal_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('skor_rumusan_masalah')->default(0); // bobot 20%
            $table->unsignedTinyInteger('skor_tinjauan_pustaka')->default(0); // bobot 15%
            $table->unsignedTinyInteger('skor_metode')->default(0); // bobot 25%
            $table->unsignedTinyInteger('skor_luaran')->default(0); // bobot 25%
            $table->unsignedTinyInteger('skor_rab')->default(0); // bobot 15%
            $table->decimal('nilai_total', 5, 2)->default(0);
            $table->enum('rekomendasi', ['layak', 'layak_dengan_revisi', 'tidak_layak'])->nullable();
            $table->text('komentar_reviewer')->nullable();
            $table->enum('status_review', ['pending', 'completed'])->default('pending');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['proposal_id', 'reviewer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_reviews');
    }
};
