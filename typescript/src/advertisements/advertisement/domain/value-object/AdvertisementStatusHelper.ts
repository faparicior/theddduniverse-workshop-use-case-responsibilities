export class AdvertisementStatus {
    private constructor(private readonly status: string) {}

    public static readonly ENABLED = new AdvertisementStatus('enabled');
    public static readonly DISABLED = new AdvertisementStatus('disabled');

    public static fromString(status: string): AdvertisementStatus {
        switch (status) {
            case 'enabled':
                return AdvertisementStatus.ENABLED;
            case 'disabled':
                return AdvertisementStatus.DISABLED;
            default:
                throw new Error(`Invalid status: ${status}`);
        }
    }

    public value(): string {
        return this.status;
    }
}