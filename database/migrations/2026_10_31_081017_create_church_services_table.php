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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('service_categories')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('day_of_week')->default('Minggu');
            $table->string('start_time')->default('09:00');
            $table->string('end_time')->nullable()->default('11:00');
            $table->string('location')->nullable();
            $table->foreignId('contact_person_id')->nullable()->constrained('contact_persons')->nullOnDelete();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
