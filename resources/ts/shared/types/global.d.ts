export {};

declare global {
    interface Window {
        miFuncionGlobal: () => void;
        MiApp?: {
            saludar: () => void;
            version: string;
        };
    }
}
