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
        // 1. Restore missing column 'archived_at'
        if (!Schema::hasColumn('commandes', 'archived_at')) {
            Schema::table('commandes', function (Blueprint $table) {
                $table->timestamp('archived_at')->nullable();
            });
        }

        // 2. Fix ENUM/Constraint for 'statut'
        // For PostgreSQL, Laravel usually creates a check constraint for enums.
        // We drop the old constraint and add a new one with all values.
        try {
            DB::statement("ALTER TABLE commandes DROP CONSTRAINT IF EXISTS commandes_statut_check");
            
            // Add new constraint with all values including 'cloturee' and 'archivee'
            DB::statement("ALTER TABLE commandes ADD CONSTRAINT commandes_statut_check 
                CHECK (statut IN ('brouillon', 'confirmee', 'en_cours', 'livree', 'annulee', 'cloturee', 'archivee'))");
                
        } catch (\Exception $e) {
            // If it fails (e.g. not Postgres or different constraint name), we try a generic approach
            // or just log it. But for this environment (pgsql), this is the likely fix.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We generally don't want to remove the column in a fix migration, 
        // but for correctness:
        // Schema::table('commandes', function (Blueprint $table) {
        //     $table->dropColumn('archived_at');
        // });
    }
};
