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
    Schema::create('sites', function (Blueprint $table) {
        $table->id();
        $table->string('favicon_image')->nullable(); // nullable, если лого не обязательно при старте
        $table->foreignId('dealer_id')
            ->constrained('dealers')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
        $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
        $table->timestamps(); 
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
