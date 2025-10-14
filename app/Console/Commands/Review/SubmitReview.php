<?php

namespace App\Console\Commands\Review;

use App\Models\AmazonReviewAccount;
use App\Models\AmazonReviewProject;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SubmitReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:submit-review {review_id}'; // php artisan app:submit-review 1
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scriptPath = storage_path('app/public/script/reviews/submit.js');
        $review_id = $this->argument('review_id');
        $project = AmazonReviewProject::find($review_id);
        $account = AmazonReviewAccount::where("account_id", $project->account_id)->first();
        $reviews = [
            "amazon_id" => $project->account_id,
            "book_asin" => $project->book_asin,
            "review_title" => $project->review_title,
            "review_description" => $project->review_description,
            "rating" => $project->rating,
        ];
        $reviews = json_encode($reviews);

        $nodePath = env('NODE_EXECUTABLE');
        $process = new Process([
            $nodePath,
            $scriptPath,

            $reviews
        ]);

        $process->setTimeout(300); 
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info('Node.js script executed successfully!');
        $this->line($process->getOutput());
    }
}
