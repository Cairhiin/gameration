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
        Schema::create('achievement_user', function (Blueprint $table) {
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class, "user_id")->constrained()->cascadeOnDelete();
            $table->timestamp('unlocked_at')->nullable();
            $table->unique(['achievement_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_user');
    }
};
