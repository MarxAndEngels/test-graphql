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
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('title', '40');
            $table->string('slug', '40');
            $table->foreignId('city_id')
                ->index()
                ->constrained('cities') // Указывает, что поле ссылается на таблицу cities
                ->cascadeOnUpdate()
                ->restrictOnDelete(); // Не даст удалить город, если к нему привязаны дилеры
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
