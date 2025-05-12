import { BASE_URL } from "../configs/config";
import { HttpMethod } from "../enums/HTTPMethods.enum";
import { Cookie } from "./cookie.utils";
import { HTTPResponse } from "../../modules/shared/domain/interfaces/HTTPResponse.interface";
import { HTTPStatusCode, getStatusPhrase } from "../enums/HTTPStatusCode.enum";
export class HttpClient {
    /**
     * Executes a generic HTTP request with token, timeout, retry logic, and response validation.
     *
     * @template T - The expected data type of the HTTP response.
     * @param {HttpMethod} method - HTTP method to use.
     * @param {string} url - Relative or absolute URL of the resource.
     * @param {string | number} [id] - Resource ID (used for PUT, PATCH, DELETE).
     * @param {Record<string, any>} [body] - Optional request payload.
     * @param {Record<string, string>} [headers] - Optional additional headers.
     * @param {number} [timeoutMs=10000] - Timeout in milliseconds (default is 10s).
     * @param {number} [retries=0] - Number of retry attempts on network failure.
     * @returns {Promise<HTTPResponse<T>>} A typed response matching the backend contract.
     */
    private static async request<T>(
        method: HttpMethod,
        url: string,
        id?: string | number,
        body?: Record<string, any>,
        headers?: Record<string, string>,
        timeoutMs: number = 30000,
        retries: number = 0
    ): Promise<HTTPResponse<T>> {
        this.validateParams(method, id, body);
        const fullUrl = this.buildUrl(url, id);
        const access_token = Cookie.get("access_token");

        if (!access_token && window.location.pathname !== "/login") {
            window.location.href = "/login";
        }

        if (BASE_URL === "") {
            return {
                type: 4,
                status: HTTPStatusCode.InvalidBaseUrl,
                phrase: getStatusPhrase(HTTPStatusCode.InvalidBaseUrl),
                message: "BASE_URL is not set.",
                timestamp: new Date().toISOString(),
                errors: {},
            };
        }

        const finalHeaders: Record<string, string> = {
            ...headers,
            Authorization: `Bearer ${access_token}`,
            ...(method !== HttpMethod.GET && method !== HttpMethod.DELETE ? { "Content-Type": "application/json" } : {}),
        };

        const fetchOptions: RequestInit = {
            method,
            headers: finalHeaders,
            body: body ? JSON.stringify(body) : undefined,
        };

        for (let attempt = 0; attempt <= retries; attempt++) {
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), timeoutMs);
            fetchOptions.signal = controller.signal;

            try {
                const response = await fetch(fullUrl, fetchOptions);
                clearTimeout(timeout);

                const parsed = await this.parseResponse<T>(response);

                if (!this.isValidBackendResponse(parsed)) {
                    return {
                        type: 4,
                        status: 500,
                        phrase: "MalformedResponse",
                        message: "Response does not match expected structure.",
                        timestamp: new Date().toISOString(),
                        errors: { raw: parsed },
                    };
                }

                return parsed;
            } catch (error: any) {
                clearTimeout(timeout);

                const isLastAttempt = attempt === retries;

                if (isLastAttempt) {
                    return {
                        type: 4,
                        status: HTTPStatusCode.NetworkError,
                        phrase: getStatusPhrase(HTTPStatusCode.NetworkError),
                        message: error?.message || "Network error",
                        timestamp: new Date().toISOString(),
                        errors: {},
                    };
                }
            }
        }
        return {
            type: 4,
            status: HTTPStatusCode.UnhandledError,
            phrase: getStatusPhrase(HTTPStatusCode.UnhandledError),
            message: "Unhandled error in HTTP client",
            timestamp: new Date().toISOString(),
            errors: {},
        };
    }

    /**
     * Executes a GET request.
     *
     * @template T - The expected data type of the HTTP response.
     * @param {string} url - Resource URL.
     * @param {Record<string, string>} [headers] - Optional headers.
     * @param {number} [timeoutMs] - Optional timeout in milliseconds.
     * @param {Record<string, any>} [params] - Optional parameters to append to the URL.
     * @returns {Promise<HTTPResponse<T>>}
     */
    static async get<T>(url: string, headers?: Record<string, string>, timeoutMs?: number, params?: Record<string, any>): Promise<HTTPResponse<T>> {
        if (params) {
            const queryString = new URLSearchParams(params).toString();
            url = `${url}?${queryString}`;
        }
        return this.request<T>(HttpMethod.GET, url, undefined, undefined, headers, timeoutMs);
    }

    /**
     * Executes a POST request with a request body.
     *
     * @template T - The expected data type of the HTTP response.
     * @param {string} url - Resource URL.
     * @param {Record<string, any>} [body] - Request payload.
     * @param {Record<string, string>} [headers] - Optional headers.
     * @param {number} [timeoutMs] - Optional timeout in milliseconds.
     * @returns {Promise<HTTPResponse<T>>}
     */
    static async post<T>(url: string, body?: Record<string, any>, headers?: Record<string, string>, timeoutMs?: number): Promise<HTTPResponse<T>> {
        return this.request<T>(HttpMethod.POST, url, undefined, body, headers, timeoutMs);
    }

    /**
     * Executes a PUT request to update a resource by ID.
     *
     * @template T - The expected data type of the HTTP response.
     * @param {string} url - Resource URL.
     * @param {string | number} id - Resource ID.
     * @param {Record<string, any>} [body] - Request payload.
     * @param {Record<string, string>} [headers] - Optional headers.
     * @param {number} [timeoutMs] - Optional timeout in milliseconds.
     * @returns {Promise<HTTPResponse<T>>}
     */
    static async put<T>(url: string, id: string | number, body?: Record<string, any>, headers?: Record<string, string>, timeoutMs?: number): Promise<HTTPResponse<T>> {
        return this.request<T>(HttpMethod.PUT, url, id, body, headers, timeoutMs);
    }

    /**
     * Executes a PATCH request to partially update a resource by ID.
     *
     * @template T - The expected data type of the HTTP response.
     * @param {string} url - Resource URL.
     * @param {string | number} id - Resource ID.
     * @param {Record<string, any>} [body] - Request payload.
     * @param {Record<string, string>} [headers] - Optional headers.
     * @param {number} [timeoutMs] - Optional timeout in milliseconds.
     * @returns {Promise<HTTPResponse<T>>}
     */
    static async patch<T>(url: string, id: string | number, body?: Record<string, any>, headers?: Record<string, string>, timeoutMs?: number): Promise<HTTPResponse<T>> {
        return this.request<T>(HttpMethod.PATCH, url, id, body, headers, timeoutMs);
    }

    /**
     * Executes a DELETE request for a resource by ID.
     *
     * @template T - The expected data type of the HTTP response.
     * @param {string} url - Resource URL.
     * @param {string | number} id - Resource ID.
     * @param {Record<string, string>} [headers] - Optional headers.
     * @param {number} [timeoutMs] - Optional timeout in milliseconds.
     * @returns {Promise<HTTPResponse<T>>}
     */
    static async delete<T>(url: string, id: string | number, headers?: Record<string, string>, timeoutMs?: number): Promise<HTTPResponse<T>> {
        return this.request<T>(HttpMethod.DELETE, url, id, undefined, headers, timeoutMs);
    }

    /**
     * Validates required parameters based on HTTP method.
     *
     * @param {HttpMethod} method - HTTP method.
     * @param {string | number} [id] - Optional resource ID.
     * @param {Record<string, any>} [body] - Optional request payload.
     * @returns {void}
     */
    private static validateParams(method: HttpMethod, id?: string | number, body?: Record<string, any>): void {
        if ([HttpMethod.PUT, HttpMethod.PATCH, HttpMethod.DELETE].includes(method) && !id) {
            throw new Error(`${method} request requires a valid resource ID.`);
        }

        if ([HttpMethod.POST, HttpMethod.PUT, HttpMethod.PATCH].includes(method) && !body) {
            console.warn(`${method} request usually requires a body payload.`);
        }
    }

    /**
     * Determines whether the URL is absolute.
     *
     * @param {string} url - The URL to check.
     * @returns {boolean}
     */
    private static isAbsoluteUrl(url: string): boolean {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }

    /**
     * Builds the full URL, handling both relative and absolute cases.
     *
     * @param {string} url - The base URL or path.
     * @param {string | number} [id] - Optional resource ID.
     * @returns {string}
     */
    private static buildUrl(url: string, id?: string | number): string {
        const isAbsolute = this.isAbsoluteUrl(url);
        const cleanedBase = BASE_URL.replace(/\/$/, "");
        const cleanedUrl = url.replace(/^\//, "");
        const finalId = id ? `/${id}` : "";
        return isAbsolute ? `${url}${finalId}` : `${cleanedBase}/${cleanedUrl}${finalId}`;
    }

    /**
     * Parses the server response based on the content type.
     *
     * @template T - The expected data type of the HTTP response.
     * @param {Response} response - The fetch response object.
     * @returns {Promise<HTTPResponse<T> | any>}
     */
    private static async parseResponse<T>(response: Response): Promise<HTTPResponse<T> | any> {
        const contentType = response.headers.get("Content-Type") || "";

        if (contentType.includes("application/json")) {
            return await response.json();
        } else {
            const text = await response.text();
            return {
                type: "4",
                status: response.status,
                phrase: "NonJSONResponse",
                message: "The server responded with non-JSON content",
                timestamp: new Date().toISOString(),
                errors: { raw: text },
            };
        }
    }

    /**
     * Checks if the given object matches the `HTTPResponse` interface.
     *
     * @param {any} obj - Object to validate.
     * @returns {obj is HTTPResponse}
     */
    private static isValidBackendResponse(obj: any): obj is HTTPResponse {
        return typeof obj === "object" && "type" in obj && "status" in obj && "phrase" in obj && "message" in obj && "timestamp" in obj;
    }
}
