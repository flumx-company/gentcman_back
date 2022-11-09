<?php

namespace Gentcmen\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected function unauthenticated($request, AuthenticationException $exception): \Illuminate\Http\JsonResponse|\Illuminate\Http\Request|\Symfony\Component\HttpFoundation\Response
    {
        if (!$request->expectsJson() || !$request->user()) {
            return $this->response(['message' => $exception->getMessage() . " Token required."], Response::HTTP_UNAUTHORIZED);
        }
        return $request;
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*') && !($e instanceof AuthenticationException))
            {
                return $this->handleApiExceptions($e);
            }
        });
    }

    public function render($request, Throwable $e): Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse
     */

    protected function handleApiExceptions(Throwable $e): \Illuminate\Http\JsonResponse
    {
        if ($e instanceof QueryException) {
            return $this->response([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        if ($e instanceof AuthorizationException) {
            return $this->response([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->response([
                'success' => false,
                'message' => 'Entry for '.str_replace('Gentcmen\Models\\', '', $e->getModel()).' not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof  NotFoundHttpException) {
            return $this->response([
                'success' => false,
                'message' => "Requested route does not exist"
            ], Response::HTTP_NOT_FOUND);
        }

        $exceptionData = [
            'success' => false,
            'error' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ],
        ];

        return $this->response($exceptionData);
    }

    /**
     * @param $data
     * @param int $httpStatus
     * @return \Illuminate\Http\JsonResponse
     */

    protected function response($data, int $httpStatus = Response::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        return response()->json($data, $httpStatus);
    }
}
