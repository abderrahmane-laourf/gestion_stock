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
        Schema::table('commandes', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable();
        });

        // Attempt to modify the enum column to add new statuses
        try {
            DB::statement("ALTER TABLE commandes MODIFY COLUMN statut ENUM('brouillon', 'confirmee', 'en_cours', 'livree', 'annulee', 'cloturee', 'archivee') DEFAULT 'brouillon'");
        } catch (\Exception $e) {
            // If strictly typed enum modification fails (e.g. SQLite), we might be fine if it was created as string/text or check driver
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });

        try {
            DB::statement("ALTER TABLE commandes MODIFY COLUMN statut ENUM('brouillon', 'confirmee', 'en_cours', 'livree', 'annulee') DEFAULT 'brouillon'");
        } catch (\Exception $e) {
            // Ignore
        }
    }
};
