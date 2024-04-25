<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param \Exception $exception
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof BaseException) {
            // Our Exceptions Should override render method
            return parent::render($request, $exception);
        }

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $exception = new ItemNotFoundException($exception->getModel(), $exception->getIds());
        }

        return $this->handleGeneralException($request, $exception) ?: parent::render($request, $exception);
    }

    public function handleGeneralException($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            return GeneralException::format($exception);
        }
    }
}
