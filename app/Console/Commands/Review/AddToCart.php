<?php

namespace App\Console\Commands\Review;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AddToCart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-to-cart {account_id}';
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scriptPath = storage_path('app/public/script/reviews/add-to-cart.js');
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
