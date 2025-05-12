import { IAuth } from "../../domain/interface/auth.interface";
import { HTTPResponse } from "../../../shared/domain/interfaces/HTTPResponse.interface";
import { HttpClient } from "../../../../shared/utils/HTTPClient.utils";
import { Cookie } from "../../../../shared/utils/cookie.utils";
import { TimeUnit } from "../../../../shared/enums/TimeUnit.enum";
export class LoginRepository implements IAuth {
    async login(email: string, password: string): Promise<HTTPResponse> {
        return await HttpClient.post<HTTPResponse>("api/v1/auth/login", { email, password });
    }

    async logout(): Promise<HTTPResponse> {
        return await HttpClient.post<HTTPResponse>("api/v1/auth/logout");
    }

    async refreshToken(): Promise<HTTPResponse> {
        let TTL: string | null = localStorage.getItem("TTL");
        let access_token: string | null = Cookie.get("access_token");
        if (!TTL || isNaN(Number(TTL))) {
            return {
                type: 4,
                status: 400, // Bad Request
                phrase: "InvalidTTL",
                message: "The TTL value is missing or invalid.",
                timestamp: new Date().toISOString(),
                data: null,
                errors: { raw: "TTL value is not a valid number." },
            };
        }
        if (!access_token) {
            return {
                type: 4,
                status: 401, // Unauthorized
                phrase: "NoAccessToken",
                message: "Access token is missing in cookies.",
                timestamp: new Date().toISOString(),
                data: null,
                errors: { raw: "The access token is not available in the cookies." },
            };
        }
        const ttlNumber = Number(TTL);
        Cookie.updateTTL("access_token", ttlNumber, TimeUnit.Seconds);
        return {
            type: 2,
            status: 200,
            phrase: "TokenRefreshed",
            message: "Access token TTL has been successfully refreshed.",
            timestamp: new Date().toISOString(),
            data: { success: true },
            errors: {},
        };
    }
}
