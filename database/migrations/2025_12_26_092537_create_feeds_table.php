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
        Schema::create('feeds', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название фида для удобства в админке
            
            // Тип фида из списка
            $table->enum('type', [
                'Yandex XML', 
                'Yandex YML', 
                'VK XML', 
                'Google XML'
            ]); 
            
            $table->string('url'); // Ссылка на XML файл фида
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeds');
    }
};
