<?php

namespace App\Ticsol\Base\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json(['code' => 1000, 'error' => true, 'message' => 'Invalid route or http method.'], 500);
        } elseif ($e instanceof HttpResponseException) {
            return response()->json(['code' => 1001, 'error' => true, 'message' => $e->getMessage()], $e->status);
        } elseif ($e instanceof AuthenticationException) {
            return response()->json(['code' => 1002, 'error' => true, 'message' => 'Unauthenticated.'], 401);
        } elseif ($e instanceof AuthorizationException) {
            return response()->json(['code' => 1003, 'error' => true, 'message' => 'This action is unauthorized.'], 401);
        } elseif ($e instanceof ValidationException) {
            return response()->json(['code' => 1004, 'error' => true, 'message' => $e->getMessage(), 'errors' => $e->errors()], $e->status);
        } elseif($e instanceof QueryException){
            return response()->json(['code' => 1006, 'error' => true, 'message' => 'Invalid query or field name.'], 400);
        }

        return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('home', ['any' => '/']));
    }
}
