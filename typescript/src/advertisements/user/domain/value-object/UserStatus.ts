export class UserStatus {
    public static readonly ENABLED = new UserStatus('enabled');
    public static readonly DISABLED = new UserStatus('disabled');

    private constructor(private readonly _value: string) {}

    public static fromString(status: string): UserStatus {
        switch (status) {
            case 'enabled':
                return UserStatus.ENABLED;
            case 'disabled':
                return UserStatus.DISABLED;
            default:
                throw new Error(`Invalid status: ${status}`);
        }
    }

    public value(): string {
        return this._value;
    }

    public isEnabled(): boolean {
        return this === UserStatus.ENABLED;
    }
}
