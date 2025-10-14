<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AmazonReviewProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read the file and extract ASINs from all_asin.txt (lines 1-471)
        $filePath = base_path('all_asin.txt');
        $asins = [];

        if (file_exists($filePath)) {
            $fileContents = file_get_contents($filePath);
            // Extract ASINs from the format 'B0XXXXXXXX'
            preg_match_all("/'(B0[A-Z0-9]+)'/", $fileContents, $matches);

            if (!empty($matches[1])) {
                $asins = $matches[1];
            }
        }

        if (empty($asins)) {
            $this->command->error('No ASINs found in all_asin.txt file!');
            return;
        }

        $now = Carbon::now();
        $updatedCount = 0;

        // Update existing records (1-471) with ASINs from the file
        for ($i = 1; $i <= 471; $i++) {
            // Use the ASIN at position $i-1 (since file lines 1-471 map to array indices 0-470)
            $asinIndex = $i - 1;

            if ($asinIndex < count($asins)) {
                // Update the record with the corresponding ASIN
                $updated = DB::table('amazon_review_projects')
                    ->where('id', $i)
                    ->update([
                        'book_asin' => $asins[$asinIndex],
                        'updated_at' => $now,
                    ]);

                if ($updated) {
                    $updatedCount++;
                }
            }
        }

        $this->command->info("Successfully updated $updatedCount Amazon Review Projects with ASINs from all_asin.txt!");
    }
}
