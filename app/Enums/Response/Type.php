<?php

namespace App\Enums\Response;

enum Type: int
{
    case INFO = 1;
    case OK = 2;
    case WARNING = 3;
    case ERROR = 4;
    /**
     * Get numeric Type code
     */
    public function getCode(): int
    {
        return $this->value;
    }
    public function logLevel(): string
    {
        return match ($this) {
            self::INFO => 'info',
            self::OK => 'info',
            self::WARNING => 'warning',
            self::ERROR => 'error',
        };
    }
}
