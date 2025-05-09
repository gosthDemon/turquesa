<?php

namespace App\Helpers\Logs;

use App\Enums\Response\Type;
use App\Enums\Types\ExceptionType;
use App\Helpers\Tools\Tools;
use \Illuminate\Support\Facades\Log as LaravelLog;
use Throwable;
use Exception;

class Log
{
    /**
     * Logs an error message along with exception details if provided.
     *
     * @param string $message The custom error message to be logged.
     * @param array|\Exception $exception An optional exception object or an array to provide additional exception details.
     *                                    If an exception is provided, it will log information such as the file, line, stack trace, 
     *                                    and the message of the exception.
     * @return void
     * 
     * This method logs the following details:
     * - File where the error occurred.
     * - Function where the error was triggered.
     * - Line number where the error occurred.
     * - Stack trace of the exception (excluding vendor files).
     * - Message of the exception.
     * - Inner exception message (if available).
     */
    public static function log(string $message, array|Exception|Throwable|null $exception = [], Type $type = Type::ERROR): void
    {
        // Initialize variables to hold exception data
        $channel = ExceptionType::getChannel($exception);

        $archivo = "";
        $funcion = "";
        $stackTrace = "";
        $line = "";
        $messageException = "";

        // Check if the provided argument is an exception
        if ($exception instanceof \Throwable) {
            $archivo = $exception->getFile();
            $funcion = $exception->getTrace()[0]['function'] ?? '';
            $line = $exception->getLine();
            $trace = explode("\n", $exception->getTraceAsString());
            // Filter out the 'vendor' stack trace lines to avoid unnecessary noise
            $stackTrace = array_filter($trace, function ($line) {
                return !str_contains($line, 'vendor');
            });
            $messageException = $exception->getMessage();
        } else {
            $archivo = $exception['file'] ?? 'unknown file';
            $funcion = $exception['function'] ?? 'unknown function';
            $line = $exception['line'] ?? 'unknown line';
            $messageException = $exception['message'] ?? '';
            $stackTrace = isset($exception['stackTrace']) ? $exception['stackTrace'] : ['No stack trace available'];
        }

        // Prepare the data array to log detailed information
        $data = [
            "Message" => $message,
            "MessageException" => $messageException,
            "InnerException" => $exception instanceof \Exception && $exception->getPrevious() ? $exception->getPrevious()->getMessage() : "",
            "StackTrace" => $stackTrace,
            "Source" => $archivo
        ];

        // Format the log entry with details
        $logEntry = sprintf(
            "in %s | %s | %s | %s",
            basename($archivo),
            $funcion,
            $line,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );


        $logMethod = ($type == Type::ERROR) ? 'error' : 'warning';
        $logChannel = Tools::isChannelAvailable($channel) ? $channel : null;
        LaravelLog::channel($logChannel)->{$logMethod}($logEntry);
    }
}