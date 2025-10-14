<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add book_asin column if it doesn't exist
        if (!Schema::hasColumn('amazon_review_projects', 'book_asin')) {
            Schema::table('amazon_review_projects', function (Blueprint $table) {
                $table->string('book_asin')->nullable()->after('project_id');
            });
        }

        // Step 2: Extract ASIN from review_link and populate book_asin
        $projects = DB::table('amazon_review_projects')->whereNotNull('review_link')->get();

        foreach ($projects as $project) {
            // Extract ASIN from URL using regex
            // Pattern: asin=XXXXX (captures the ASIN value after asin=)
            if (preg_match('/asin=([A-Z0-9]+)/i', $project->review_link, $matches)) {
                $asin = $matches[1];
                DB::table('amazon_review_projects')
                    ->where('id', $project->id)
                    ->update(['book_asin' => $asin]);
            }
        }

        // Step 3: Remove review_link column
        Schema::table('amazon_review_projects', function (Blueprint $table) {
            $table->dropColumn('review_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back review_link column
        Schema::table('amazon_review_projects', function (Blueprint $table) {
            $table->text('review_link')->nullable()->after('book_asin');
        });

        // Reconstruct review_link from book_asin
        $projects = DB::table('amazon_review_projects')->whereNotNull('book_asin')->get();

        foreach ($projects as $project) {
            $reviewLink = "https://www.amazon.com/review/create-review/?ie=UTF8&channel=glance-detail&asin=" . $project->book_asin;
            DB::table('amazon_review_projects')
                ->where('id', $project->id)
                ->update(['review_link' => $reviewLink]);
        }

        // Remove book_asin column
        Schema::table('amazon_review_projects', function (Blueprint $table) {
            $table->dropColumn('book_asin');
        });
    }
};
