<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BackgroundJobs\JobRunner;

class RunJob extends Command
{
    protected $signature = 'job:run {class} {method} {params} {retryAttempts=3}';
    protected $description = 'Run a background job';

    public function handle()
    {
        $class = $this->argument('class');
        $method = $this->argument('method');
        $params = json_decode($this->argument('params'), true);
        $retryAttempts = $this->argument('retryAttempts');

        $attempts = 0;

        while ($attempts < $retryAttempts) {
            try {
                JobRunner::execute($class, $method, $params);
                return 0;
            } catch (\Exception $e) {
                $attempts++;
                sleep(1); // Delay before retry
            }
        }

        return 1;
    }
}
