<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mlbb_lineup_statistics', function (Blueprint $table) {
            $table->id();
            
            // Lineup composition (sorted array of 5 hero slugs for consistency)
            $table->json('lineup_heroes'); // e.g., ["atlas", "fanny", "kagura", "melissa", "tigreal"]
            $table->string('lineup_hash')->unique(); // MD5 hash of sorted heroes for quick lookup
            
            // Team composition breakdown
            $table->string('role_composition'); // e.g., "Tank-Fighter-Mage-Marksman-Support"
            
            // Statistics
            $table->integer('times_analyzed')->default(0); // How many times this lineup was analyzed
            $table->integer('times_won')->default(0); // Simulated/predicted wins
            $table->integer('times_lost')->default(0); // Simulated/predicted losses
            $table->decimal('win_rate', 5, 2)->default(0.00); // Calculated win rate percentage
            
            // Average matchup scores
            $table->decimal('avg_matchup_score', 5, 2)->default(0.00); // Average advantage score
            $table->decimal('avg_early_game', 5, 2)->default(0.00);
            $table->decimal('avg_mid_game', 5, 2)->default(0.00);
            $table->decimal('avg_late_game', 5, 2)->default(0.00);
            
            // Team synergy metrics
            $table->decimal('synergy_score', 5, 2)->default(0.00);
            $table->decimal('durability_avg', 5, 2)->default(0.00);
            $table->decimal('offense_avg', 5, 2)->default(0.00);
            $table->decimal('control_avg', 5, 2)->default(0.00);
            
            // Common opponents faced (JSON array of lineup hashes)
            $table->json('common_opponents')->nullable();
            
            // Last analyzed
            $table->timestamp('last_analyzed_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('lineup_hash');
            $table->index('win_rate');
            $table->index('times_analyzed');
            $table->index('last_analyzed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mlbb_lineup_statistics');
    }
};
