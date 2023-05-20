<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Models\Error;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $this->storeError($e);
        });
    }

    private function storeError(Throwable $exception)
    {
        Error::create([
            'message' => $exception->getMessage(), 
            'stack_trace' => $exception->getTraceAsString(), 
            'file' => $exception->getFile(), 
            'line' => $exception->getLine(), 
            'faced_by' => null, 
            'created_at' => date('Y-m-d H:i:s'), 
            'fixed_at' => null
        ]);
    }
}
