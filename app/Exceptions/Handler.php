<?php

namespace App\Exceptions;

use ErrorException;
use http\Exception\BadMethodCallException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;


class Handler extends ExceptionHandler
{
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
     * @param \Exception $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */

    public function render($request, Throwable $exception)
    {
        info($exception->getMessage());
        if ($exception instanceof ModelNotFoundException){
            return response()->json(["status" => 404, 'message' => "Not Found Your Targeted Data"], Response::HTTP_NOT_FOUND);
        }
        if ($exception instanceof QueryException){
            return response()->json(["status" => 500, 'message' => "Internal Server Error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if ($exception instanceof MethodNotAllowedHttpException){
            return response()->json(["status" => 405, 'message' => "Method Not Allowed"], Response::HTTP_METHOD_NOT_ALLOWED);
        }
        if ($exception instanceof ErrorException){
            return response()->json(["status" => 500, 'message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if ($exception instanceof PermissionAlreadyExists){
            return response()->json(["status" => 422, 'message' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($exception instanceof NotFoundHttpException){
            return response()->json(["status" => 404, 'message' => "URL is not recognized"], Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $exception);
    }

}
