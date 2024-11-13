<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

class BackgroundJobRunner
{
    protected $class;
    protected $method;
    protected $parameters;
    protected $maxRetries = 3; // Configurable retry limit

    public function __construct($class, $method, $parameters = [])
    {
        $this->class = $class;
        $this->method = $method;
        $this->parameters = $parameters;
    }

    public function execute()
    {
        try {
            if (!class_exists($this->class) || !method_exists($this->class, $this->method)) {
                throw new \Exception("Invalid class or method.");
            }

            $instance = new $this->class;
            call_user_func_array([$instance, $this->method], $this->parameters);

            Log::channel('background_jobs')->info("Job executed successfully", [
                'class' => $this->class,
                'method' => $this->method,
                'status' => 'success',
                'timestamp' => now(),
            ]);
        } catch (\Exception $e) {
            $this->retryJob($e);
        }
    }

    protected function retryJob($e)
    {
        static $attempts = 0;
        if ($attempts < $this->maxRetries) {
            $attempts++;
            sleep(2); // delay before retrying
            $this->execute();
        } else {
            Log::channel('background_jobs_errors')->error("Job failed after multiple attempts", [
                'class' => $this->class,
                'method' => $this->method,
                'status' => 'failed',
                'timestamp' => now(),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
