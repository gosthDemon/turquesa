<?php

namespace App\Enums\Response;

enum Status: int
{
    // 1xx: Informational
    case Continue = 100;
    case SwitchingProtocols = 101;
    case Processing = 102;
    case EarlyHints = 103;

        // 2xx: Success
    case OK = 200;
    case Created = 201;
    case Accepted = 202;
    case NonAuthoritativeInformation = 203;
    case NoContent = 204;
    case ResetContent = 205;
    case PartialContent = 206;

        // 3xx: Redirection
    case MultipleChoices = 300;
    case MovedPermanently = 301;
    case Found = 302;
    case NotModified = 304;
    case TemporaryRedirect = 307;
    case PermanentRedirect = 308;

        // 4xx: Client Errors
    case BadRequest = 400;
    case Unauthorized = 401;
    case Forbidden = 403;
    case NotFound = 404;
    case MethodNotAllowed = 405;
    case RequestTimeout = 408;
    case Conflicts = 409;
    case UnprocessableEntity = 422;
    case Locked = 423;
    case TooManyRequests = 429;

        // 5xx: Server Errors
    case InternalServerError = 500;
    case NotImplemented = 501;
    case BadGateway = 502;
    case ServiceUnavailable = 503;
    case GatewayTimeout = 504;

    /**
     * Get numeric HTTP status code
     */
    public function getCode(): int
    {
        return $this->value;
    }

    /**
     * Get HTTP status phrase
     */
    public function getPhrase(): string
    {
        return match ($this) {
            // 1xx
            self::Continue => 'Continue',
            self::SwitchingProtocols => 'Switching Protocols',
            self::Processing => 'Processing',
            self::EarlyHints => 'Early Hints',

            // 2xx
            self::OK => 'OK',
            self::Created => 'Created',
            self::Accepted => 'Accepted',
            self::NonAuthoritativeInformation => 'Non-Authoritative Information',
            self::NoContent => 'No Content',
            self::ResetContent => 'Reset Content',
            self::PartialContent => 'Partial Content',

            // 3xx
            self::MultipleChoices => 'Multiple Choices',
            self::MovedPermanently => 'Moved Permanently',
            self::Found => 'Found',
            self::NotModified => 'Not Modified',
            self::TemporaryRedirect => 'Temporary Redirect',
            self::PermanentRedirect => 'Permanent Redirect',

            // 4xx
            self::BadRequest => 'Bad Request',
            self::Unauthorized => 'Unauthorized',
            self::Forbidden => 'Forbidden',
            self::NotFound => 'Not Found',
            self::MethodNotAllowed => 'Method Not Allowed',
            self::RequestTimeout => 'Request Timeout',
            self::Conflicts => "Conflicts",
            self::UnprocessableEntity => "Unprocessable Entity",
            self::Locked => 'Locked',
            self::TooManyRequests => 'Too Many Requests',

            // 5xx
            self::InternalServerError => 'Internal Server Error',
            self::NotImplemented => 'Not Implemented',
            self::BadGateway => 'Bad Gateway',
            self::ServiceUnavailable => 'Service Unavailable',
            self::GatewayTimeout => 'Gateway Timeout',
        };
    }
}
