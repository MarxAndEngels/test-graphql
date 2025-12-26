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
        $table->string('url')->unique(); // URL сайта (например, mysite.ru)
        $table->string('favicon_image')->nullable(); // nullable, если лого не обязательно при старте
        // 1. Поле для связи с "главным" сайтом (родитель)
        $table->foreignId('parent_id')
            ->nullable() // Если null — значит это главный сайт
            ->constrained('sites') // Ссылается на эту же таблицу
            ->nullOnDelete(); // Если главный сайт удалят, зеркала станут самостоятельными
        $table->boolean('is_main')->default(false);
        $table->boolean('is_active')->default(true);
        $table->foreignId('dealer_id')
            ->constrained('dealers')
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
