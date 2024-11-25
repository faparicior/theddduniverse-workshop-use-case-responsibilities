export class AdvertisementApprovalStatus {
    private constructor(private readonly status: string) {}

    public static readonly PENDING_FOR_APPROVAL = new AdvertisementApprovalStatus('pending_for_approval');
    public static readonly APPROVED = new AdvertisementApprovalStatus('approved');

    public static fromString(status: string): AdvertisementApprovalStatus {
        switch (status) {
            case 'pending_for_approval':
                return AdvertisementApprovalStatus.PENDING_FOR_APPROVAL;
            case 'approved':
                return AdvertisementApprovalStatus.APPROVED;
            default:
                throw new Error(`Invalid status: ${status}`);
        }
    }

    public value(): string {
        return this.status;
    }
}
