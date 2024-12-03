import { DomainException } from "../../../../common/domain/DomainException";

export class MemberDoesNotExistsException extends DomainException {
    private static readonly MEMBER_DOES_NOT_EXISTS_MESSAGE = 'Member does not exists';

    private constructor(message: string) {
        super(message);
    }

    public static build(): MemberDoesNotExistsException {
        return new MemberDoesNotExistsException(MemberDoesNotExistsException.MEMBER_DOES_NOT_EXISTS_MESSAGE);
    }

    public getMessage(): string {
        return this.message;
    }
}
