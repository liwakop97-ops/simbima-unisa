<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('kode_registrasi', 30)->unique();
            $table->foreignId('grant_scheme_id')->constrained('grant_schemes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul', 500);
            $table->text('abstrak');
            $table->string('kata_kunci');
            $table->unsignedTinyInteger('lama_kegiatan_tahun')->default(1);
            $table->decimal('dana_usulan', 15, 2);
            $table->decimal('dana_disetujui', 15, 2)->nullable();
            $table->string('file_proposal')->nullable();
            $table->string('file_revisi_proposal')->nullable();
            $table->string('nomor_kontrak', 100)->nullable();
            $table->enum('status', [
                'draft',
                'submitted',
                'admin_verified',
                'admin_rejected',
                'under_review',
                'accepted',
                'accepted_revision',
                'rejected',
                'revision_submitted',
                'in_progress',
                'progress_submitted',
                'progress_revision',
                'progress_approved',
                'final_submitted',
                'completed'
            ])->default('draft');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
