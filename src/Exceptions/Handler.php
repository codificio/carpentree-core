<?php

namespace Carpentree\Core\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Plank\Mediable\HandlesMediaUploadExceptions;

class Handler extends ExceptionHandler
{
    use HandlesMediaUploadExceptions;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
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
    public function render($request, Exception $exception)
    {
        $exception = $this->transformMediaUploadException($exception);

        return parent::render($request, $exception);
    }

    /**
     * Convert the given exception to an array.
     *
     * @param  \Exception  $e
     * @return array
     */
    protected function convertExceptionToArray(Exception $e)
    {
        return config('app.debug') ? [
            'errors' => [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => collect($e->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all(),
            ],
        ] : [
            'errors' => [
                'message' => $this->isHttpException($e) ? $e->getMessage() : 'Server Error',
            ]
        ];
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        $errors = [];
        foreach ($exception->errors() as $key => $value) {
            Arr::set($errors, $key, $value);
        }

        return response()->json([
            'errors' => [
                'message' => $exception->getMessage(),
                'validation' =>  $errors
            ]
        ], $exception->status);
    }
}
