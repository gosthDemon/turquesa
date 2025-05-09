<?php

namespace App\Enums\Types;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;

enum ExceptionType: int
{
    case GenericException = 1;
    case ValidationException = 2;
    case AuthenticationException = 3;
    case AuthorizationException = 4;
    case ModelNotFoundException = 5;
    case QueryException = 6;
    case UnknownException = 7;

    /**
     * Determine the log channel based on the provided Throwable instance.
     *
     * This method inspects the exception and matches it against common Laravel
     * exception types. It returns the appropriate log channel for the exception.
     *
     * @param Throwable $exception The exception instance to evaluate.
     * @return string The log channel where the exception should be logged.
     */
    public static function getChannel(Throwable $exception): string
    {
        if ($exception instanceof ValidationException) {
            return 'validation';
        }

        if ($exception instanceof AuthenticationException) {
            return 'auth';
        }

        if ($exception instanceof AuthorizationException) {
            return 'authorization';
        }

        if ($exception instanceof ModelNotFoundException) {
            return 'model_not_found';
        }

        if ($exception instanceof QueryException) {
            return 'query';
        }

        // Si es una instancia de Exception o cualquier otra clase que no esté definida
        if ($exception instanceof Exception) {
            return 'generic';
        }

        return 'unknown';
    }
}