<?php

namespace App\Exceptions;

use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class GeneralException
{
    public static function format(Throwable $exception)
    {
        $exceptionClass = get_class($exception);

        $showTrace = false;

        switch ($exceptionClass) {
            case NotFoundHttpException::class:
                $type = ErrorTypes::ROUTE_NOT_FOUND;
                break;
            case ItemNotFoundException::class:
                $type = ErrorTypes::ITEM_NOT_FOUND;
                $message = $exception->getMessage();
                break;
            case MethodNotAllowedHttpException::class:
                $type = ErrorTypes::METHOD_NOT_ALLOWED;
                break;
            case ValidationException::class:
                assert($exception instanceof ValidationException);
                $type = ErrorTypes::INPUT_VALIDATION;
                $message = $exception->getMessage();
                $errors = $exception->errors();
                break;
            case AuthenticationException::class:
                $message = $exception->getMessage();
                $type = ErrorTypes::UNAUTHENTICATED;
                break;
            case AuthorizationException::class:
                $type = ErrorTypes::FORBIDDEN;
                break;
            case TokenMismatchException::class:
                $type = ErrorTypes::TOKEN_MISMATCH;
                $showTrace = true;
                break;
            case UnauthorizedException::class:
                $type = ErrorTypes::FORBIDDEN;
                $message = $exception->getMessage();
                $showTrace = false;
                break;
            default:
                $type = ErrorTypes::UNKNOWN;
                $message = $exception->getMessage();
                $showTrace = true;
                break;
        }

        $response = [
            'type' => $type,
            'message' => $message ?? ErrorTypes::message($type),
            'errors' => $errors ?? [],
            'trace' => config('app.debug') && $showTrace ? $exception->getTrace() : []
        ];

        return response(
            collect($response)->filter(fn($value) => ! empty($value))->toArray()
        )->setStatusCode(ErrorTypes::status($type));
    }
}
