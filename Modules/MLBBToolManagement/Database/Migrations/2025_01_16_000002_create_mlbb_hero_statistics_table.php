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
        Schema::create('mlbb_hero_statistics', function (Blueprint $table) {
            $table->id();
            
            // Hero reference
            $table->string('hero_slug')->unique();
            $table->string('hero_name');
            $table->string('hero_role');
            
            // Pick statistics
            $table->integer('times_picked')->default(0);
            $table->integer('times_banned')->default(0);
            $table->integer('times_won')->default(0);
            $table->integer('times_lost')->default(0);
            
            // Win rate
            $table->decimal('win_rate', 5, 2)->default(0.00);
            $table->decimal('pick_rate', 5, 2)->default(0.00);
            $table->decimal('ban_rate', 5, 2)->default(0.00);
            
            // Performance metrics (averages from matchups)
            $table->decimal('avg_matchup_impact', 5, 2)->default(0.00);
            $table->decimal('avg_early_game_impact', 5, 2)->default(0.00);
            $table->decimal('avg_mid_game_impact', 5, 2)->default(0.00);
            $table->decimal('avg_late_game_impact', 5, 2)->default(0.00);
            
            // Common picks with (JSON array of hero slugs and count)
            $table->json('frequently_picked_with')->nullable();
            
            // Common counters faced (JSON array of hero slugs and count)
            $table->json('frequently_countered_by')->nullable();
            
            // Last picked/analyzed
            $table->timestamp('last_picked_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('hero_slug');
            $table->index('win_rate');
            $table->index('pick_rate');
            $table->index('times_picked');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mlbb_hero_statistics');
    }
};
