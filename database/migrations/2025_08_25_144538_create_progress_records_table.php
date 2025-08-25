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
        Schema::create('progress_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_uid')->unique(); // UUID từ frontend
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('kid_id')->nullable(); // ID từ frontend
            $table->string('lesson');
            $table->integer('score');
            $table->string('age')->nullable();
            $table->string('name');
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('kid_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_records');
    }
};
