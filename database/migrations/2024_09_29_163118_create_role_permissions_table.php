<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->comment('Разрешения ролей');

            $table->id();
            $table->foreignId('permission_id')->references('id')->on('permissions');
            $table->foreignId('role_id')->references('id')->on('roles');

            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->softDeletes('deleted_at');
            $table->foreignId('deleted_by')->nullable()->references('id')->on('users');

            $table->unique(['permission_id', 'role_id'], 'ix_permission_id_role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('role_permissions');
    }
};
