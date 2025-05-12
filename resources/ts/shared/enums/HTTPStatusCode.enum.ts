export enum HTTPStatusCode {
    // 0xx: OfSystem (not standard)

    InvalidBaseUrl = 1,
    NetworkError = 2,
    UnhandledError = 3,

    // 1xx: Informational
    Continue = 100,
    SwitchingProtocols = 101,
    Processing = 102,
    EarlyHints = 103,

    // 2xx: Success
    OK = 200,
    Created = 201,
    Accepted = 202,
    NonAuthoritativeInformation = 203,
    NoContent = 204,
    ResetContent = 205,
    PartialContent = 206,

    // 3xx: Redirection
    MultipleChoices = 300,
    MovedPermanently = 301,
    Found = 302,
    NotModified = 304,
    TemporaryRedirect = 307,
    PermanentRedirect = 308,

    // 4xx: Client Errors
    BadRequest = 400,
    Unauthorized = 401,
    Forbidden = 403,
    NotFound = 404,
    MethodNotAllowed = 405,
    RequestTimeout = 408,
    Conflicts = 409,
    UnprocessableEntity = 422,
    Locked = 423,
    TooManyRequests = 429,

    // 5xx: Server Errors
    InternalServerError = 500,
    NotImplemented = 501,
    BadGateway = 502,
    ServiceUnavailable = 503,
    GatewayTimeout = 504,
}

// Function to get numeric HTTP status code
export function getStatusCode(status: HTTPStatusCode): number {
    return status;
}

// Function to get HTTP status phrase
export function getStatusPhrase(status: HTTPStatusCode): string {
    switch (status) {
        // 0xx: OfSystem (not standard)
        case HTTPStatusCode.InvalidBaseUrl:
            return "Invalid Base URL";
        case HTTPStatusCode.NetworkError:
            return "Network Error";
        case HTTPStatusCode.UnhandledError:
            return "Unhandled Error";

        // 1xx
        case HTTPStatusCode.Continue:
            return "Continue";
        case HTTPStatusCode.SwitchingProtocols:
            return "Switching Protocols";
        case HTTPStatusCode.Processing:
            return "Processing";
        case HTTPStatusCode.EarlyHints:
            return "Early Hints";

        // 2xx
        case HTTPStatusCode.OK:
            return "OK";
        case HTTPStatusCode.Created:
            return "Created";
        case HTTPStatusCode.Accepted:
            return "Accepted";
        case HTTPStatusCode.NonAuthoritativeInformation:
            return "Non-Authoritative Information";
        case HTTPStatusCode.NoContent:
            return "No Content";
        case HTTPStatusCode.ResetContent:
            return "Reset Content";
        case HTTPStatusCode.PartialContent:
            return "Partial Content";

        // 3xx
        case HTTPStatusCode.MultipleChoices:
            return "Multiple Choices";
        case HTTPStatusCode.MovedPermanently:
            return "Moved Permanently";
        case HTTPStatusCode.Found:
            return "Found";
        case HTTPStatusCode.NotModified:
            return "Not Modified";
        case HTTPStatusCode.TemporaryRedirect:
            return "Temporary Redirect";
        case HTTPStatusCode.PermanentRedirect:
            return "Permanent Redirect";

        // 4xx
        case HTTPStatusCode.BadRequest:
            return "Bad Request";
        case HTTPStatusCode.Unauthorized:
            return "Unauthorized";
        case HTTPStatusCode.Forbidden:
            return "Forbidden";
        case HTTPStatusCode.NotFound:
            return "Not Found";
        case HTTPStatusCode.MethodNotAllowed:
            return "Method Not Allowed";
        case HTTPStatusCode.RequestTimeout:
            return "Request Timeout";
        case HTTPStatusCode.Conflicts:
            return "Conflicts";
        case HTTPStatusCode.UnprocessableEntity:
            return "Unprocessable Entity";
        case HTTPStatusCode.Locked:
            return "Locked";
        case HTTPStatusCode.TooManyRequests:
            return "Too Many Requests";

        // 5xx
        case HTTPStatusCode.InternalServerError:
            return "Internal Server Error";
        case HTTPStatusCode.NotImplemented:
            return "Not Implemented";
        case HTTPStatusCode.BadGateway:
            return "Bad Gateway";
        case HTTPStatusCode.ServiceUnavailable:
            return "Service Unavailable";
        case HTTPStatusCode.GatewayTimeout:
            return "Gateway Timeout";

        default:
            return "Unknown Status";
    }
}
