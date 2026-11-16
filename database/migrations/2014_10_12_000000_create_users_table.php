<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('nidn_nip', 30)->nullable()->unique();
            $table->string('institusi_prodi')->nullable();
            $table->string('jabatan_fungsional')->nullable();
            $table->string('sinta_id', 30)->nullable();
            $table->enum('role', ['admin', 'dosen', 'reviewer'])->default('dosen');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
