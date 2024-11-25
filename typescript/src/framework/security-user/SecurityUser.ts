export class SecurityUser {
    public static readonly STATUS_ACTIVE = 'active';
    public static readonly STATUS_INACTIVE = 'inactive';

    constructor(
        private id: string,
        private email: string,
        private password: string,
        private role: string,
        private status: string,
    ) {}

    public getId(): string {
        return this.id;
    }

    public getEmail(): string {
        return this.email;
    }

    public getPassword(): string {
        return this.password;
    }

    public getRole(): string {
        return this.role;
    }

    public getStatus(): string {
        return this.status;
    }
}
