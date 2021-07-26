<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'code'    => $exception->status,
            // 'message' => $exception->getMessage(),
            'message' => __('Los datos proporcinados son validos.'),
           // 'errors'  => $this->transformErrors($exception),
           'errors' => $exception->errors(),

        ], $exception->status);
    }

    // transform the error messages,
    // private function transformErrors(ValidationException $exception)
    // {
    //     $errors = [];

    //     foreach ($exception->errors() as $field => $message) {
    //        $errors[] = [
    //            'field' => $field,
    //            'message' => $message[0],
    //        ];
    //     }

    //     return $errors;
    // }

        public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => __('Modeo no encontrados.'),
                'error' => 'Resource not found'
            ], 404);
        }

        if ($exception instanceof RouteNotFoundException) {
            return response()->json([
                'message' =>  false,
                'error' => 'No tiene permiso para acceder a esta ruta'
            ], 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' =>  false,
                'error' => 'No tiene permiso para acceder a esta ruta'
            ], 404);
        }



        return parent::render($request, $exception);
}


}
