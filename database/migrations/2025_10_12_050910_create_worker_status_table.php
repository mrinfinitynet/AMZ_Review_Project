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
        Schema::create('worker_status', function (Blueprint $table) {
            $table->id();
            $table->string('worker_id', 100)->unique();
            $table->enum('status', ['online', 'offline', 'busy'])->default('offline');
            $table->unsignedBigInteger('current_task_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('last_heartbeat')->nullable();
            $table->string('chrome_version', 50)->nullable();
            $table->integer('total_tasks_completed')->default(0);
            $table->integer('total_tasks_failed')->default(0);
            $table->timestamps();

            $table->index('worker_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_status');
    }
};
