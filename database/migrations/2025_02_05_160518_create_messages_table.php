<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->text('body')->nullable()->comment('Сообщение');

            // FK и IDx
            $table->foreignId('user_id')->nullable()->index()->comment('ID пользователя')->constrained(
                'users'
            )->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->index()->comment('ID родительского тега')->constrained(
                'messages'
            )->cascadeOnUpdate()->nullOnDelete();

            // Мягкое удаление
            $table->softDeletes()->comment('Дата удаления');

            $table->timestamp('created_at')->useCurrent()->comment('Дата создания');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('Дата редактирования');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
