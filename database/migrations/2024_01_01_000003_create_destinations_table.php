<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: destinations
 * Tabel utama destinasi wisata dengan koordinat GPS untuk Leaflet Map.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Admin pembuat
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('description_en')->nullable();
            $table->string('address');
            $table->string('region');                          // Kabupaten/Kota
            $table->string('province')->default('Jawa Tengah');
            $table->decimal('latitude', 10, 7);               // Koordinat GPS — dipakai Leaflet
            $table->decimal('longitude', 10, 7);              // Koordinat GPS — dipakai Leaflet
            $table->string('thumbnail')->nullable();           // Gambar utama untuk card & marker popup
            $table->string('open_hours')->nullable();          // Contoh: "08:00 - 17:00"
            $table->string('ticket_price')->nullable();        // Contoh: "Rp 15.000"
            $table->string('ticket_price_en')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->unsignedTinyInteger('rating_avg')->default(0); // 0-5, diperbarui tiap ada review
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_popular')->default(false);     // Tampil di highlight beranda
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();                  // Data tambahan fleksibel
            $table->timestamps();

            $table->index(['latitude', 'longitude']);           // Index untuk query "wisata terdekat"
            $table->index(['category_id', 'is_active']);
            $table->index('region');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
