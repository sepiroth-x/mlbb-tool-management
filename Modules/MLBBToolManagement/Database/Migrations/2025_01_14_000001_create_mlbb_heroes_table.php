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
        Schema::create('mlbb_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->enum('role', ['Tank', 'Fighter', 'Assassin', 'Mage', 'Marksman', 'Support']);
            $table->string('image_path')->nullable();
            
            // Stats (1-10 scale)
            $table->tinyInteger('durability')->default(5);
            $table->tinyInteger('offense')->default(5);
            $table->tinyInteger('control')->default(5);
            $table->tinyInteger('difficulty')->default(5);
            
            // Game Phase Strength (1-10 scale)
            $table->tinyInteger('early_game')->default(5);
            $table->tinyInteger('mid_game')->default(5);
            $table->tinyInteger('late_game')->default(5);
            
            // Specialty tags (JSON array)
            $table->json('specialties')->nullable(); // e.g., ["Initiator", "Crowd Control", "Burst Damage"]
            
            // Counter relationships (JSON arrays of hero IDs/slugs)
            $table->json('strong_against')->nullable();
            $table->json('weak_against')->nullable();
            $table->json('synergy_with')->nullable();
            
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index('role');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mlbb_heroes');
    }
};
