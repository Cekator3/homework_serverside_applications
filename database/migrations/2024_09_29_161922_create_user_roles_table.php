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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->comment('Роли пользователей');

            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('role_id')->references('id')->on('roles');

            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->softDeletes('deleted_at');
            $table->foreignId('deleted_by')->nullable()->references('id')->on('users');

            $table->unique(['user_id', 'role_id'], 'ix_user_id_role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
