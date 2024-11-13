<?php

use App\Jobs\BackgroundJobRunner;

function runBackgroundJob($class, $method, $params = [], $priority = 0)
{
    $allowedClasses = config('background_jobs.allowed_classes');
    if (!in_array($class, $allowedClasses)) {
        throw new Exception("Class not allowed for background job execution.");
    }

    $jobData = [
        'class' => $class,
        'method' => $method,
        'params' => $params,
        'priority' => $priority,
    ];

    $command = 'php ' . base_path('artisan') . " job:execute '" . json_encode($jobData) . "'";

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        pclose(popen("start /B " . $command, "r"));
    } else {
        exec($command . " > /dev/null &");
    }
}
