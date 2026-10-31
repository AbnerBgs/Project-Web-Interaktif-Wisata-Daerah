<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: events
 * Event & Festival Daerah yang bisa terhubung ke destinasi tertentu.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('description_en')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('location');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('organizer')->nullable();
            $table->string('ticket_info')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
