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
        Schema::create('permissions', function (Blueprint $table) {
            $table->comment('Разрешения');

            $table->id();
            $table->text('name')->unique('ix_permissions_name');
            $table->text('description')->nullable();
            $table->text('code')->unique('ix_permissions_code');

            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->softDeletes('deleted_at');
            $table->foreignId('deleted_by')->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
