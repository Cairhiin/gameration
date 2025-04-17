<?php

use App\Models\Person;
use App\Models\Series;
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
        Schema::create('series_author', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Series::class, 'series_id')
                ->constrained('series')
                ->cascadeOnDelete();
            $table->foreignIdFor(Person::class, 'author_id')
                ->constrained('persons')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_author');
    }
};
