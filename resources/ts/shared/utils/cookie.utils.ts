import { SameSite } from "../enums/SameSiteCookie.enum";
import { TimeUnit } from "../enums/TimeUnit.enum";

/**
 * Utility class for managing cookies in the browser.
 * Limitación: Solo es posible acceder a nombre y valor desde document.cookie.
 */
export class Cookie {
    /**
     * Gets the value of a cookie by name.
     * @param name - The name of the cookie.
     * @returns The decoded value or null if not found.
     */
    static get(name: string): string | null {
        const cookies = document.cookie.split("; ");
        const cookie = cookies.find((row) => row.startsWith(`${encodeURIComponent(name)}=`));
        return cookie ? decodeURIComponent(cookie.split("=").slice(1).join("=")) : null;
    }

    /**
     * Gets the parsed value of a cookie as JSON.
     * @param name - The name of the cookie.
     * @returns The parsed object or null if invalid or not found.
     */
    static getAsJSON<T>(name: string): T | null {
        const value = this.get(name);
        try {
            return value ? (JSON.parse(value) as T) : null;
        } catch {
            return null;
        }
    }

    /**
     * Creates or updates a cookie.
     * @param name - The name of the cookie.
     * @param value - The value of the cookie.
     * @param options - Optional cookie attributes.
     */
    static set(
        name: string,
        value: string,
        options?: {
            expires?: number | Date;
            maxAge?: number; // preferido en lugar de expires
            path?: string;
            secure?: boolean;
            sameSite?: SameSite;
        }
    ) {
        const isDev = import.meta.env?.VITE_ENV === "local" || location.hostname === "localhost";

        const secure = isDev ? false : options?.secure;

        let cookieString = `${encodeURIComponent(name)}=${encodeURIComponent(value)}`;

        if (options?.expires) {
            const date = typeof options.expires === "number" ? new Date(Date.now() + options.expires * 1000) : options.expires;
            cookieString += `; expires=${date.toUTCString()}`;
        }

        if (options?.maxAge !== undefined) {
            cookieString += `; max-age=${Math.floor(options.maxAge)}`;
        }

        cookieString += `; path=${options?.path ?? "/"}`;

        if (options?.secure) {
            if (location.protocol !== "https:") {
                console.warn("Estás configurando una cookie 'Secure' en una conexión no segura.");
            }
            cookieString += `; Secure`;
        }

        if (options?.sameSite) {
            cookieString += `; SameSite=${options.sameSite}`;
        }

        document.cookie = cookieString;
    }

    /**
     * Deletes a cookie by name.
     * @param name - The name of the cookie.
     * @param path - Optional path. Default is "/".
     */
    static remove(name: string, path: string = "/") {
        document.cookie = `${encodeURIComponent(name)}=; path=${path}; expires=Thu, 01 Jan 1970 00:00:00 GMT`;
    }

    /**
     * Checks if a cookie exists by name.
     * @param name - The name of the cookie.
     * @returns True if exists, false otherwise.
     */
    static exists(name: string): boolean {
        return document.cookie.split("; ").some((cookie) => cookie.startsWith(`${encodeURIComponent(name)}=`));
    }

    /**
     * Updates the TTL (Time to Live) of a cookie by extending its duration.
     * @param name - The name of the cookie.
     * @param amount - The time amount to add.
     * @param unit - The unit of time.
     */
    static updateTTL(name: string, amount: number, unit: TimeUnit) {
        const value = this.get(name);
        if (!value) return;

        let expires: Date;
        switch (unit) {
            case TimeUnit.Seconds:
                expires = new Date(Date.now() + amount * 1000);
                break;
            case TimeUnit.Minutes:
                expires = new Date(Date.now() + amount * 60 * 1000);
                break;
            case TimeUnit.Hours:
                expires = new Date(Date.now() + amount * 60 * 60 * 1000);
                break;
            case TimeUnit.Days:
                expires = new Date(Date.now() + amount * 24 * 60 * 60 * 1000);
                break;
            case TimeUnit.Permanent:
                expires = new Date("9999-12-31T23:59:59.000Z");
                break;
            default:
                throw new Error("Invalid time unit.");
        }

        this.set(name, value, { expires });
    }

    /**
     * Sets the cookie expiration to a specific Date object.
     * @param name - The cookie name.
     * @param date - The date to expire.
     */
    static setExpirationToDate(name: string, date: Date) {
        const value = this.get(name);
        if (value !== null) {
            this.set(name, value, { expires: date });
        }
    }

    /**
     * Parses a raw cookie string (e.g., from SSR headers) into an object.
     * @param raw - The raw cookie string.
     * @returns A record of name/value pairs.
     */
    static parse(raw: string): Record<string, string> {
        return raw.split("; ").reduce((acc, pair) => {
            const index = pair.indexOf("=");
            if (index !== -1) {
                const name = decodeURIComponent(pair.substring(0, index));
                const value = decodeURIComponent(pair.substring(index + 1));
                acc[name] = value;
            }
            return acc;
        }, {} as Record<string, string>);
    }
}
