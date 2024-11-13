<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;

class ExecuteJob extends Command
{
    protected $signature = 'job:execute {jobData}';
    protected $description = 'Execute a specified job in the background';

    public function handle()
    {
        $jobData = json_decode($this->argument('jobData'), true);
        $class = $jobData['class'];
        $method = $jobData['method'];
        $params = $jobData['params'];
        $priority = $jobData['priority'] ?? 0;

        $retryAttempts = config('background_jobs.retry_attempts', 3);
        $delaySeconds = config('background_jobs.delay_seconds', 0);

        $attempts = 0;
        $success = false;

        while ($attempts < $retryAttempts && !$success) {
            $attempts++;
            try {
                // Delay if configured
                if ($attempts == 1 && $delaySeconds > 0) {
                    sleep($delaySeconds);
                }

                $jobInstance = new $class;
                call_user_func_array([$jobInstance, $method], $params);

                Log::info("Job executed successfully: {$class}@{$method}");
                $success = true;
            } catch (Exception $e) {
                Log::error("Attempt {$attempts}: Job failed: {$class}@{$method} - " . $e->getMessage());
            }
        }

        if (!$success) {
            Log::error("Job failed after {$retryAttempts} attempts: {$class}@{$method}");
        }
    }
}
