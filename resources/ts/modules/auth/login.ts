import { BASE_URL } from "../../shared/configs/config";
import { ResponseType } from "../../shared/enums/HTTPResponseType.enum";
import { SameSite } from "../../shared/enums/SameSiteCookie.enum";
import { HTTPResponse } from "../shared/domain/interfaces/HTTPResponse.interface";
import { Cookie } from "../../shared/utils/cookie.utils";
import { HttpClient } from "../../shared/utils/HTTPClient.utils";
import { BearerDTO } from "./infraestructure/DTO's/bearer.dto";

class Login {
    private loginButton: HTMLElement | null;
    private errorMessageContainer: HTMLElement | null;

    constructor() {
        this.loginButton = document.getElementById("login-button");
        this.errorMessageContainer = document.getElementById("error-form-input");
        this.initializeEvents();
    }

    private initializeEvents(): void {
        if (this.loginButton) {
            this.loginButton.addEventListener("click", this.login.bind(this));
        }
    }

    private async login(): Promise<void> {
        try {
            const email = (document.getElementById("email") as HTMLInputElement).value;
            const password = (document.getElementById("password") as HTMLInputElement).value;

            const response: HTTPResponse = await HttpClient.post<HTTPResponse>("api/v1/auth/login", { email, password });

            if (response.type !== ResponseType.OK) {
                this.errorMessageContainer!.innerHTML = response.message;
                return;
            }

            const bearer: BearerDTO = new BearerDTO(response.data);

            Cookie.set("session", "abc123", {
                maxAge: bearer.expires_at,
                path: "/",
                secure: true,
                sameSite: SameSite.Lax,
            });

            localStorage.setItem("TTL", bearer.expires_at.toString());
            localStorage.setItem("TokenType", bearer.token_type);

            window.location.href = "/dashboard";
        } catch (error: any) {
            this.errorMessageContainer!.innerHTML = "An unexpected error occurred. Please try again later.";
        }
    }
}
new Login();
