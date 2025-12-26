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
        Schema::create('site_feed', function (Blueprint $table) {
            $table->id();
            // Связь с сайтом
            $table->foreignId('site_id')
                ->constrained()
                ->cascadeOnDelete()
                ->restrictOnDelete();
            // Связь с фидом
            $table->foreignId('feed_id')
                ->constrained()
                ->cascadeOnDelete()
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_feed');
    }
};
