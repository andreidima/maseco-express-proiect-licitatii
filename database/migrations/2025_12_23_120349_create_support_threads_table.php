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
        Schema::create('support_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject', 150);
            $table->enum('type', ['chat', 'problem'])->default('chat');
            $table->enum('status', ['open', 'pending', 'resolved'])->default('open');
            $table->string('problem_category')->nullable();
            $table->string('problem_severity')->nullable();
            $table->text('problem_summary')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_threads');
    }
};
