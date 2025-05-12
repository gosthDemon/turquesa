import { HTTPResponse } from "../../../shared/domain/interfaces/HTTPResponse.interface";

export interface IAuth {
    login(email: string, password: string): Promise<HTTPResponse>;
    logout(): Promise<HTTPResponse>;
    refreshToken(): Promise<HTTPResponse>;
}
