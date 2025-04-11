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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('published_at');
            $table->foreignIdFor(\App\Models\Publisher::class);
            $table->foreignIdFor(\App\Models\Series::class)->nullable();
            $table->foreignIdFor(\App\Models\User::class);
            $table->smallInteger('series_book_number')->nullable();
            $table->string('image')->nullable();
            $table->string('ISBN')->unique();
            $table->integer('pages')->nullable();
            $table->string('time', 5)->nullable();
            $table->enum('type', ['physical', 'audiobook', 'ebook']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
