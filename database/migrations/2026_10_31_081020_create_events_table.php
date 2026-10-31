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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('event_date');
            $table->string('start_time');
            $table->string('end_time')->nullable();
            $table->string('venue');
            $table->text('description');
            $table->string('link_url')->nullable();
            $table->string('link_label')->nullable();
            $table->foreignId('contact_person_id')->nullable()->constrained('contact_persons')->nullOnDelete();
            $table->string('cover_path')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
