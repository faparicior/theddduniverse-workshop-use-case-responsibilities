import {DomainException} from "../../../../common/domain/DomainException";

export class AdminWithIncorrectCivicCenterException extends DomainException {
    private static readonly ADMIN_DOES_NOT_BELONG_TO_THE_SAME_CIVIC_CENTER = 'Admin does not belong to the same civic center';

    private constructor(message: string) {
        super(message);
    }

    public static differentCivicCenterFromMember(): AdminWithIncorrectCivicCenterException {
        return new AdminWithIncorrectCivicCenterException(AdminWithIncorrectCivicCenterException.ADMIN_DOES_NOT_BELONG_TO_THE_SAME_CIVIC_CENTER);
    }

    public getMessage(): string {
        return this.message;
    }
}
