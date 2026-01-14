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
        Schema::create('mlbb_matches', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Finals - Team A vs Team B"
            $table->string('team_a_name')->default('Team A');
            $table->string('team_b_name')->default('Team B');
            
            // Match state
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->enum('current_phase', ['ban', 'pick', 'locked'])->default('ban');
            
            // Picked and banned heroes (JSON arrays of hero IDs/slugs)
            $table->json('team_a_picks')->nullable(); // Array of 5 hero picks
            $table->json('team_b_picks')->nullable();
            $table->json('team_a_bans')->nullable(); // Array of 3 hero bans
            $table->json('team_b_bans')->nullable();
            
            // Action history for undo functionality (JSON array)
            $table->json('action_history')->nullable();
            
            // Metadata
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mlbb_matches');
    }
};
