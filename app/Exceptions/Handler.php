<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $model = (new \ReflectionClass($e->getModel()))->getShortName();

            return response([
                'error' => "{$model} not found.",
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        else if ($e instanceof \InvalidArgumentException) {
            return response([
                'error' => $e->getMessage(),
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }

        else if ($e instanceof HttpResponseException) {
            if ($e->getResponse()->getStatusCode() === Response::HTTP_FORBIDDEN) {
                return response([
                    'error' => 'Forbidden',
                    'status' => Response::HTTP_FORBIDDEN
                ], Response::HTTP_FORBIDDEN);
            }
        }

        else if ($e instanceof NotFoundHttpException) {
            return response([
                'error' => 'Not found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        else if ($e instanceof Exception) {
            if (!method_exists($e, 'getResponse') || !$e->getResponse()->getContent()) {
                if ($e->getCode() > 0) {
                    return response([
                        'error' => $e->getMessage(),
                        'status' => $e->getCode()
                    ], $e->getCode());
                }
            }
            return response ([
                'error' => $e->getMessage()
            ]);
        }

        else {
            return response([
                'error' => 'There was an error'
            ], 422);
        }

//        if ($e instanceof GeneralException) {
//            return response([
//                'error' => $e->errorMessage,
//                'status' => Response::HTTP_BAD_REQUEST
//            ], Response::HTTP_BAD_REQUEST);
//        }

        return parent::render($request, $e);
    }
}
