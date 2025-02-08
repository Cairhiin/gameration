<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('name');
            $table->string('username')->unique();
            $table->text('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            /* $table->string('two_factor_secret')->nullable();
            $table->string('two_factor_recovery_codes')->nullable(); */
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->foreignIdFor(\App\Models\Role::class, "role_id")->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
