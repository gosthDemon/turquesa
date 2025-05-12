import { IAuth } from "../../domain/interface/auth.interface";
import { HTTPResponse } from "../../../shared/domain/interfaces/HTTPResponse.interface";
import { ResponseType } from "../../../../shared/enums/HTTPResponseType.enum";
import { SameSite } from "../../../../shared/enums/SameSiteCookie.enum";
import { BearerDTO } from "../../infraestructure/DTO's/bearer.dto";
import { Cookie } from "../../../../shared/utils/cookie.utils";
import { TimeUnit } from "../../../../shared/enums/TimeUnit.enum";

export class LoginUseCase {
    constructor(private loginRepository: IAuth) {}

    async login(email: string, password: string): Promise<HTTPResponse> {
        try {
            const response: HTTPResponse = await this.loginRepository.login(email, password);

            if (response.type !== ResponseType.OK) {
                return response;
            }

            const bearer: BearerDTO = new BearerDTO(response.data);

            Cookie.set("access_token", bearer.access_token, {
                maxAge: bearer.expires_at,
                path: "/",
                secure: true,
                sameSite: SameSite.Lax,
            });

            localStorage.setItem("TTL", bearer.expires_at.toString());
            localStorage.setItem("TokenType", bearer.token_type);

            return {
                type: 2,
                status: 200,
                phrase: "LoginSuccess",
                message: "Login successful. Redirecting to dashboard.",
                timestamp: new Date().toISOString(),
                data: {},
            };
        } catch (error: any) {
            return {
                type: 4,
                status: 500,
                phrase: "UnexpectedError",
                message: "An unexpected error occurred. Please try again later.",
                timestamp: new Date().toISOString(),
                data: null,
                errors: { raw: error.message || "An unexpected error occurred." },
            };
        }
    }

    async logout(): Promise<HTTPResponse> {
        return await this.loginRepository.logout();
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

//Tarea -> Abstraer la logica de la respuesta de la API en una clase. Utils.
