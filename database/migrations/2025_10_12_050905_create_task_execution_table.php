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
        Schema::create('task_execution', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('review_id')->nullable();
            $table->enum('task_type', ['review', 'add_to_cart', 'check_account'])->default('review');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('progress')->default(0);
            $table->string('message', 500)->nullable();
            $table->string('worker_id', 100)->nullable();
            $table->string('worker_ip', 45)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->string('screenshot_path', 500)->nullable();
            $table->integer('attempts')->default(0);
            $table->timestamps();

            $table->index('review_id');
            $table->index('status');
            $table->index('worker_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_execution');
    }
};
