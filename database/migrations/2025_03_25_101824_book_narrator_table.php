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
        Schema::create('book_narrator', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Book::class, 'book_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Person::class, 'narrator_id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_narrator');
    }
};
