<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackgroundJobController extends Controller
{
    public function runJob(Request $request)
    {
        try {
            // You can get parameters dynamically from the request
            $jobClass = \App\Jobs\YourApprovedJobClass::class;
            $method = 'methodName';
            $params = ['param' => $request->input('param')];
            
            // Running the background job
            runBackgroundJob($jobClass, $method, $params);

            return response()->json(['message' => 'Job is running in the background.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
