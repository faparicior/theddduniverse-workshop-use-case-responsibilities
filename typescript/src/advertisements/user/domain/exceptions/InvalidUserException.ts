import {DomainException} from "../../../../common/domain/DomainException";

export class InvalidUserException extends DomainException {
    private static readonly INVALID_USER_MESSAGE = 'Invalid user';

    private constructor(message: string) {
        super(message);
    }

    public static build(message: string): InvalidUserException {
        return new InvalidUserException(InvalidUserException.INVALID_USER_MESSAGE);
    }

    public getMessage(): string {
        return this.message;
    }
}
