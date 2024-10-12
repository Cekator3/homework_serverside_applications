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
        Schema::create('logs_requests', function (Blueprint $table) {
            $table->comment('Логи запросов пользователей');

            $table->id();
            $table->string('url')->comment('URL HTTP-запроса');
            $table->string('http_method')->comment('Метод HTTP-запроса');
            $table->string('controller')->comment('Контроллер, обработавший HTTP-запрос');
            $table->string('controller_method')->comment('Метод контроллера, обрабатывающего HTTP-запрос');
            $table->json('request_body')->nullable()->comment('Тело HTTP-запроса');
            $table->json('request_headers')->nullable()->comment('Заголовки HTTP-запроса');
            $table->unsignedBigInteger('user_id')->nullable()->comment('Идентификатор пользователя, сделавшего HTTP-запрос');
            $table->string('user_ip')->comment('IP-адрес пользователя, сделавшего HTTP-запрос');
            $table->string('user_agent')->nullable()->comment('Содержимое HTTP-заголовка User-Agent');
            $table->integer('response_status')->comment('Статус HTTP-ответа');
            $table->json('response_body')->nullable()->comment('Тело HTTP-ответа');
            $table->json('response_headers')->nullable()->comment('Заголовки HTTP-ответа');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_requests');
    }
};
