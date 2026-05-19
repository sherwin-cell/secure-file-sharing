<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('secure_files', function (Blueprint $table) {
            $table->longText('encrypted_content')->nullable()->after('encryption_iv');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('secure_files', function (Blueprint $table) {
            $table->dropColumn('encrypted_content');
        });
    }
};