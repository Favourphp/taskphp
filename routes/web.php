<?php

use App\Http\Controllers\BackgroundJobController;

Route::post('/run-job', [BackgroundJobController::class, 'runJob']);
