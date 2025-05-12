/**
 * Represents the standard structure of an API response returned by the backend.
 * This generic interface allows mapping the response object consistently across the application.
 *
 * @template T - The type of the `data` field, which represents the actual payload returned in the response.
 */
export interface HTTPResponse<T = any> {
    /**
     * Internal code provided by the backend. It may be business-specific and can differ from the HTTP status code.
     * @example 1:info, 2:ok, 3:warning, 4:error
     */
    type: number;

    /**
     * HTTP status code of the response.
     * @example 200, 404, 500
     */
    status: number;

    /**
     * A human-readable phrase or short description corresponding to the status.
     * Useful for client-side messages or logs.
     * @example "OK", "NotFound", "InternalServerError"
     */
    phrase: string;

    /**
     * A more detailed message about the result of the request.
     * Typically used for UI messages or debugging.
     * @example "The user was created successfully."
     */
    message: string;

    /**
     * ISO timestamp indicating when the response was generated.
     * @example "2025-05-11T12:34:56.789Z"
     */
    timestamp: string;

    /**
     * The actual payload returned by the backend.
     * Can be any type depending on the endpoint, such as an object, array, or primitive value.
     * Optional: some responses may not include data.
     * @example { id: 1, name: "John Doe" }
     */
    data?: T;

    /**
     * An object representing validation or business errors, usually returned with 4xx codes.
     * The keys represent field names or error codes, and the values can be strings or nested structures.
     * Optional: only present when there are errors.
     * @example { email: "Invalid email address", password: "Password too short" }
     */
    errors?: Record<string, any>;
}
