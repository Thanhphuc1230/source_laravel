<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if (strpos($exception->getMessage(), 'setCookie() on null') !== false) {
            auth()->logout();

            return redirect()->route('getLogin')->with(['error' => 'Phiên làm việc đã hết hạn, vui lòng đăng nhập lại.']);
        }

        return parent::render($request, $exception);
    }
}
