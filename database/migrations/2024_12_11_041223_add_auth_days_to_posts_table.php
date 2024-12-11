<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('days_for_create')->after('image_url')->default(1);

            // FK IDx
            $table->foreignId('auth_id')->after('id')->index()->default(1)->constrained('users')->cascadeOnUpdate(
            )->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('days_for_create');
            $table->dropColumn('auth_id');
        });
    }
};
