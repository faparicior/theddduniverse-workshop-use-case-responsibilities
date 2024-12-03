export class SecurityUser {
    public static readonly STATUS_ACTIVE = 'active';
    public static readonly STATUS_INACTIVE = 'inactive';

    constructor(
        private _id: string,
        private _email: string,
        private _password: string,
        private _role: string,
        private _status: string,
    ) {}

    public id(): string {
        return this._id;
    }

    public email(): string {
        return this._email;
    }

    public password(): string {
        return this._password;
    }

    public role(): string {
        return this._role;
    }

    public status(): string {
        return this._status;
    }
}
