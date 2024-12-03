import { DomainException } from "../../../../common/domain/DomainException";

export class MemberAlreadyExistsException extends DomainException {
    private static readonly MEMBER_EXISTS_MESSAGE = 'Member already exists';

    private constructor(message: string) {
        super(message);
    }

    public static build(): MemberAlreadyExistsException {
        return new MemberAlreadyExistsException(MemberAlreadyExistsException.MEMBER_EXISTS_MESSAGE);
    }

    public getMessage(): string {
        return this.message;
    }
}
