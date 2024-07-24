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
        Schema::create('games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->smallInteger("avg_rating")->unsigned()->default(0);
            $table->bigInteger("rating_count")->unsigned()->default(0);
            $table->string("name");
            $table->string("description");
            $table->foreignIdFor(\App\Models\Developer::class, "developer_id")->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Publisher::class, "publisher_id")->constrained()->cascadeOnDelete();
            $table->string("image")->nullable();
            $table->date("released")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
