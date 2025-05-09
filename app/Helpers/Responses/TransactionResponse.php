<?php

namespace App\Helpers\Responses;

use App\Enums\Response\Type;
use App\Enums\Response\Status;
use App\Helpers\Logs\Log;
use Illuminate\Http\JsonResponse;
use Throwable;
use Exception;

class TransactionResponse
{
    public readonly Type $type;
    public readonly string $message;
    public readonly Status $status;
    public readonly string $phrase;
    public readonly string $timestamp;
    public readonly bool $loggin;
    public array $data = [];
    public array $errors = [];

    private function __construct(Status $status, Type $type, string $message, array|Exception|Throwable|null $data = [], bool $loggin = true)
    {
        if (($type == Type::ERROR || $type == Type::WARNING) && $loggin) {
            Log::log($message, $data, $type);
        }
        $this->status = $status ?? Status::InternalServerError;
        $this->type = $type;
        $this->message = $message;
        $this->loggin = $loggin;
        $this->phrase = $status->getPhrase();
        $this->timestamp = now()->toDateTimeString();

        if ($this->type == Type::OK || $this->type == Type::INFO) {
            $this->data = $data;
        } else {
            if ($data instanceof Throwable) {
                $this->errors = $this->data instanceof \Illuminate\Validation\ValidationException ? $this->data->errors() : [];
                if (empty($this->errors)) {
                    $this->errors["message"] = $data->getMessage();
                }
                if (config('app.debug') == true) {
                    $this->errors['debug'] = [
                        'message' => $data->getMessage(),
                        'file' => $data->getFile(),
                        'line' => $data->getLine(),
                    ];
                }
            } else {
                $this->errors = $data;
            }
        }
    }

    public function json(): JsonResponse
    {
        $responseData = [
            "type" => $this->type->getCode(),
            "status" => $this->status->getCode(),
            "phrase" => $this->phrase,
            "message" => $this->message,
            "timestamp" => $this->timestamp,
        ];
        if (!empty($this->data)) {
            $responseData["data"] = $this->data;
        }
        if (!empty($this->errors)) {
            $responseData["errors"] = $this->errors;
        }
        return response()->json($responseData, $this->status->getCode());
    }

    public static function INFO(Status $status, string $message, array $data = []): TransactionResponse
    {
        return new self($status, Type::INFO, $message, $data, false);
    }

    public static function OK(Status $status, string $message, array $data = []): TransactionResponse
    {
        return new self($status, Type::OK, $message, $data, false);
    }

    public static function WARNING(Status $status, string $message, array $data = [], bool $loggin = false): TransactionResponse
    {
        if (((empty($data) || $loggin) && config("app.debug"))) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $caller = $backtrace[1] ?? [];
            $data["debug"] = [
                'file' => $caller['class'] . ".php" ?? 'unknown file',
                'line' => $caller['line'] ?? 'unknown line',
                'function' => $caller['function'] ?? 'unknown function',
            ];
        }
        return new self($status, Type::WARNING, $message, $data, $loggin);
    }

    public static function ERROR(Status $status, string $message, array|Exception|Throwable|null $exception, bool $loggin = true): TransactionResponse
    {
        if (is_array($exception) && config("app.debug")) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $caller = $backtrace[1] ?? [];
            $exception["debug"] = [
                'file' => $caller['class'] . ".php" ?? 'unknown file',
                'line' => $caller['line'] ?? 'unknown line',
                'function' => $caller['function'] ?? 'unknown function',
            ];
        }
        return new self($status, Type::ERROR, $message, $exception, $loggin);
    }
}