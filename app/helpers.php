<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('runBackgroundJob')) {
    function runBackgroundJob($class, $method, $params)
    {
        try {
            // Log job start
            Log::info('Starting background job', [
                'class' => $class,
                'method' => $method,
                'params' => $params
            ]);

            // Your job execution code
            $job = new $class();
            call_user_func_array([$job, $method], $params);

            // Log job completion
            Log::info('Background job completed successfully', [
                'class' => $class,
                'method' => $method
            ]);
        } catch (\Exception $e) {
            // Log job failure in background_jobs_errors log
            Log::channel('background_jobs_errors')->error('Background job failed', [
                'class' => $class,
                'method' => $method,
                'error' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString()
            ]);
        }
    }
}
