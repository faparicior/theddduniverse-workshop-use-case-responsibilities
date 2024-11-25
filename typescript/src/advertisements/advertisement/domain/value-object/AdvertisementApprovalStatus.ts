export enum AdvertisementApprovalStatus {
    PENDING_FOR_APPROVAL = 'pending_for_approval',
    APPROVED = 'approved'
}

export class AdvertisementApprovalStatusHelper {
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

    public static value(status: AdvertisementApprovalStatus): string {
        return status;
    }
}
