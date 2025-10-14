<?php

namespace App\Console\Commands\Review;

use App\Models\AmazonReviewAccount;
use App\Models\AmazonReviewProject;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CheckAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-account {account_id}'; // php artisan app:check-review 1
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scriptPath = storage_path('app/public/script/reviews/account.js');
        $account_id = $this->argument('account_id');

        $nodePath = env('NODE_EXECUTABLE');
        $process = new Process([
            $nodePath,
            $scriptPath,

            $account_id
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
