<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secure_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('original_name');         // Original filename shown to user
            $table->string('stored_name');           // Encrypted filename on disk
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size'); // Size in bytes
            $table->string('integrity_hash');        // SHA-256 of original file (before encryption)
            $table->string('encryption_iv');         // AES IV (base64 encoded), stored per-file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secure_files');
    }
};