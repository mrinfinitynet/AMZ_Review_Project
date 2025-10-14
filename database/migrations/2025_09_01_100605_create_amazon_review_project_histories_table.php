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
        Schema::create('amazon_review_project_histories', function (Blueprint $table) {
            $table->id();
            $table->string("review_id")->nullable();
            $table->string("project_id")->nullable();
            $table->string("account_id")->nullable();
            $table->string("rating")->nullable();
            $table->string("msg")->nullable();
            $table->string("status")->nullable();
            $table->string("type")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_review_project_histories');
    }
};
