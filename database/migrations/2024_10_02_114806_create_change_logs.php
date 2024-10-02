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
        Schema::create('change_logs', function (Blueprint $table) {
            $table->comment('Логи изменений сущностей');

            $table->id();
            $table->string('entity_name')->comment('Имя сущности (например, User)');
            $table->unsignedBigInteger('entity_id');
            $table->jsonb('old_values')->comment('Сущность до изменения');
            $table->jsonb('new_values')->comment('Сущность после изменения');

            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_logs');
    }
};
