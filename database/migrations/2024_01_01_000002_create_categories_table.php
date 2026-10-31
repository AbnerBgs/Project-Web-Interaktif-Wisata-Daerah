<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: categories
 * Kategori wisata: Pantai, Gunung, Budaya, Kuliner, Religi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // Nama kategori (Indonesia)
            $table->string('name_en')->nullable();         // Nama kategori (Inggris)
            $table->string('slug')->unique();
            $table->string('icon')->nullable();            // CSS icon class atau path SVG
            $table->string('color', 20)->default('#3b82f6'); // Warna marker di peta
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
