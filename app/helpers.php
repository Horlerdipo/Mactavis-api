<?php

//LOG ERROR AND ITS SEVERITY||$severity="fatal|info"
use Illuminate\Support\Facades\Log;

if (! function_exists('log_error')) {
    function log_error($exception, $severity = 'info', $abort = true, $message = 'Something went wrong'): void
    {
        if ($severity == 'info') {
            Log::info($exception);
        } else {
            Log::critical($exception);
        }

        if ($abort) {
            abort(500, $message);
        }
    }
}
