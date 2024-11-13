<?php

namespace App\Jobs;

use Exception;

class TestErrorJob
{
    public function performTask($message)
    {
        // Deliberate error for testing
        throw new Exception('Test error: ' . $message);
    }
}
