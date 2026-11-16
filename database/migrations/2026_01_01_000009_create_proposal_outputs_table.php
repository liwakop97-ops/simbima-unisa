<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposal_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->cascadeOnDelete();
            $table->enum('jenis_luaran', [
                'jurnal_nasional',
                'jurnal_internasional',
                'prosiding',
                'hki_paten',
                'buku',
                'teknologi_tepat_guna'
            ]);
            $table->string('judul_luaran');
            $table->string('nama_jurnal_penerbit')->nullable();
            $table->enum('status_capaian', ['draft', 'submitted', 'under_review', 'accepted', 'published', 'granted'])->default('submitted');
            $table->string('url_bukti')->nullable();
            $table->string('file_bukti')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_outputs');
    }
};
