<?php

return [
    'allowed_classes' => [
        \App\Jobs\YourApprovedJobClass::class,
    ],
    'retry_attempts' => 3,
    'delay_seconds' => 0, // Delay in seconds before starting a job (optional)
];
