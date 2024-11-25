export class UserStatus {
    public static readonly ENABLED = new UserStatus('enabled');
    public static readonly DISABLED = new UserStatus('disabled');

    private constructor(private readonly value: string) {}

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

    public getValue(): string {
        return this.value;
    }

    public isEnabled(): boolean {
        return this === UserStatus.ENABLED;
    }
}
