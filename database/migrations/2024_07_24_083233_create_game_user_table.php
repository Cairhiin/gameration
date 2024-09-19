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
        Schema::create('game_user', function (Blueprint $table) {
            $table->primary(['game_id', 'user_id']);
            $table->foreignIdFor(\App\Models\Game::class, "game_id")->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, "user_id")->constrained()->cascadeOnDelete();
            $table->double("rating", 2, 1)->max(5)->min(0)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
