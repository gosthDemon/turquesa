/**
 * Defines the SameSite policy for cookies to control cross-site behavior.
 * Improves security by restricting how cookies are sent with requests.
 */
export enum SameSite {
    Strict = "Strict",
    Lax = "Lax",
    None = "None",
}
