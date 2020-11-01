<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e) {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e) {
        if($this->isHttpException($e)) {
            if($e->getStatusCode() == 404) {
                if(str_contains($request->url(), ".png")
                    || str_contains($request->url(), ".jpg")
                    || str_contains($request->url(), ".jpeg")
                    || str_contains($request->url(), ".svg")) return response()->redirectTo('/storage/img/question.png');
                return response()->view('errors.404', [], 404);
            }
            if($e->getStatusCode() == 403)
                return response()->view('errors.403', [], 403);
            if($e->getStatusCode() == 500)
                return response()->view('errors.500', [], 500);
            if($e->getStatusCode() == 503)
                return response()->view('errors.503', [], 503);
        }
        return response()->view('errors.500');
    }

}
