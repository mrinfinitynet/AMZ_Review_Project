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
        Schema::create('amazon_review_accounts', function (Blueprint $table) {
            $table->id();
            $table->string("account_id")->nullable();
            $table->string("account_name")->nullable();
            $table->string("account_email")->nullable();
            $table->string("account_password")->nullable();
            $table->string("total_review")->nullable();
            $table->string("last_review")->nullable();
            $table->string("last_checking")->nullable();
            $table->string("type")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_review_accounts');
    }
};
