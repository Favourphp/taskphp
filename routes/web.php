<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\SampleJob;

Route::get('/test-job', function () {
    runBackgroundJob(\App\Jobs\SampleJob::class, 'performTask', ['Hello, World!']);
    return 'Background job initiated!';
});
