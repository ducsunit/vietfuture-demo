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
        Schema::create('community_comments', function (Blueprint $table) {
            $table->id();
            $table->string('comment_uid')->unique(); // UUID từ frontend
            $table->foreignId('thread_id')->constrained('community_threads')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('content');
            $table->string('author', 100);
            $table->timestamps();
            
            $table->index(['thread_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_comments');
    }
};
