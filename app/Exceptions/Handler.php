<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler {
    protected $dontReport = [//
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception) // <-- USE Throwable HERE
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception) {

        $url = explode("/", request()->url());
        $is_api = array_search("api", $url);
        if ($is_api > 0)
            return _response("", $exception->getMessage(), false);
        return parent::render($request, $exception);
    }
}
