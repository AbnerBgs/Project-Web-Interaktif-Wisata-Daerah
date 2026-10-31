<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: reviews
 * Ulasan pengunjung dengan sistem moderasi (pending/approved/rejected).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // null = tamu anonim
            $table->string('reviewer_name');              // Nama tampil (bisa dari user atau input manual)
            $table->string('reviewer_email')->nullable();
            $table->unsignedTinyInteger('rating');        // 1–5 bintang
            $table->string('title')->nullable();
            $table->text('comment');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();       // Catatan admin saat moderasi
            $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('moderated_at')->nullable();
            $table->timestamps();

            $table->index(['destination_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
