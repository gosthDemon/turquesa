interface HttpError extends Error {
    status?: number;
    data?: any;
}
