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
        Schema::create('daily_verses', function (Blueprint $table) {
            $table->id();
            $table->date('verse_date')->nullable();
            $table->string('book');
            $table->integer('chapter');
            $table->integer('verse_from');
            $table->integer('verse_to')->nullable();
            $table->text('text');
            $table->string('translation')->default('TB');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_verses');
    }
};
