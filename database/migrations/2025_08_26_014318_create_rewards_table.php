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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('reward_id')->unique(); // "stk-phao", "bd-hero", "bg-ocean"
            $table->string('name'); // "Phao cứu hộ"
            $table->string('emoji'); // "🛟"
            $table->string('type'); // "sticker", "badge", "background"
            $table->integer('points'); // 10, 15, 20...
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};