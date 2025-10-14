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
        Schema::table('amazon_review_projects', function (Blueprint $table) {
            $table->text('review_link')->nullable()->after('book_asin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amazon_review_projects', function (Blueprint $table) {
            $table->dropColumn('review_link');
        });
    }
};
