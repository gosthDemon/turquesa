/**
 * Represents the Data Transfer Object (DTO) for the access token.
 * This class is used to store and transfer the response from the backend after a successful login.
 * The backend returns this object containing an `access_token`, `token_type`, and the expiration timestamp of the token.
 *
 * @class BearerDTO
 */
export class BearerDTO {
    access_token: string;
    token_type: string;
    expires_at: number;

    /**
     * Creates a new instance of BearerDTO.
     *
     * @param {Partial<BearerDTO>} data - Optional object to initialize the class with.
     */
    constructor(data: Partial<BearerDTO>) {
        this.access_token = data.access_token || "";
        this.token_type = data.token_type || "Bearer";
        this.expires_at = data.expires_at || 0;
    }
}
