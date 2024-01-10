export interface User {
    id: number;
    name: string;
    email: string;
    invite_token: string | null;
    is_admin: boolean;
}
