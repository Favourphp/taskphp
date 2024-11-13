<?php

namespace App\BackgroundJobs;

use Illuminate\Support\Facades\Log;
use Exception;

class JobRunner
{
    public static function execute($className, $methodName, $parameters = [])
    {
        try {
            if (!class_exists($className) || !method_exists($className, $methodName)) {
                throw new Exception("Class or method does not exist.");
            }

            $jobInstance = new $className;
            $result = call_user_func_array([$jobInstance, $methodName], $parameters);

            Log::info("Job executed successfully.", [
                'class' => $className,
                'method' => $methodName,
                'status' => 'success',
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error("Job execution failed.", [
                'class' => $className,
                'method' => $methodName,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
