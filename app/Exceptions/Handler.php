<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{

    use ApiResponser;
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
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Throwable
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Verificando si la exepcion es de tipo validacion ejecutara la sobrescritura del metodo convertValidationExceptionToResponse
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        // Execpcion de modelo no encuntrado, la lanza el findOrFail
        if ($exception instanceof ModelNotFoundException) {
            $model = class_basename($exception->getModel());
            return $this->errorResponse('El recurso solicitado no existe, en el modelo ' . $model, 404);
        }

        // Exepcion de autenticacion
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        // Exepcion de autorizacion
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse('No posee permisos para ejecutar esta accion - ' . $exception->getMessage(), 403);
        }

        // Exepcion de pagina no encontrada
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('No se encontro la URL especificada', 404);
        }

        // Expecion de verbo no habilitado o no permitido al endpoint
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('No es permitido el metodo', 404);
        }

        // Cualquier otra excepcion de tipo Http -> generalizando
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        // Execpcion de motor de base de datos, para este caso al intentar eliminar un objeto con relaciones activas
        // Obteniendo el codigo de error 1451
        if ($exception instanceof QueryException) {
            $codigoErroBd = $exception->errorInfo[1];
            if ($codigoErroBd == 1451) {
                return $this->errorResponse('No se puede eliminar el recurso solicitado, ya que esta relacionado con otros modelos.', 409);
            }
        }

        // Si esta en debug o desarrollo que muestre todos los errores
        if (env('APP_DEBUG')){
            return parent::render($request, $exception);
        }

        // Cualquier otra exepcion inesperada
        return $this->errorResponse('Error inesperado, Intente luego', 500);

    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param \Illuminate\Validation\ValidationException $e
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('No Autenticado.', 401);
    }

}
