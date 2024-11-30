<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->text('name'); // Наименование файла
            $table->text('description')->nullable(); // Описание файла
            $table->text('format'); // Формат файла (например, jpg, png, pdf)
            $table->unsignedBigInteger('size'); // Размер файла в байтах
            $table->text('path'); // Ссылка к файлу на сервере
            $table->foreignId('created_by')->references('id')->on('users');
            $table->timestamps(); // Служебные поля created_at, updated_at
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
