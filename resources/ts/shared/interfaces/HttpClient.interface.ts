import { HttpMethod } from "../enums/HTTPMethods.enum";

interface RequestOptions {
    method?: HttpMethod;
    headers?: Record<string, string>;
    body?: any;
    token?: string;
    retries?: number;
    timeoutMs?: number;
}
