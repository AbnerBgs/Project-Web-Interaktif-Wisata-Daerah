<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Galeri foto destinasi (multiple images per destinasi)
        Schema::create('destination_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->string('caption_en')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->timestamps();

            $table->index('destination_id');
        });

        // Master fasilitas (Toilet, Parkir, Mushola, dll)
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Pivot: fasilitas yang dimiliki tiap destinasi
        Schema::create('destination_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->unique(['destination_id', 'facility_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destination_facilities');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('destination_images');
    }
};
