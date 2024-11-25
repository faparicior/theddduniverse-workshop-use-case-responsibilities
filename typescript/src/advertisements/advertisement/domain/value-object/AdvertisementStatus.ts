export enum AdvertisementStatus {
    ENABLED = 'enabled',
    DISABLED = 'disabled'
}

export class AdvertisementStatusHelper {
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

    public static value(status: AdvertisementStatus): string {
        return status;
    }
}
