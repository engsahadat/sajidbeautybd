<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix the id column to be auto-increment
        Schema::table('settings', function (Blueprint $table) {
            // Drop and recreate the id column as auto-increment
            DB::statement('ALTER TABLE settings MODIFY id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to rollback as this is a fix
    }
};
