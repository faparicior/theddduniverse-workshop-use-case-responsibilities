import { DomainException } from "../../../../common/domain/DomainException";

export class UnauthorizedUserException extends DomainException {
    private static readonly USER_UNAUTHORIZED: string = 'User unauthorized';

    private constructor(message: string) {
        super(message);
        this.name = 'UnauthorizedUserException';
        Object.setPrototypeOf(this, new.target.prototype);
    }

    public static build(): UnauthorizedUserException {
        return new UnauthorizedUserException(this.USER_UNAUTHORIZED);
    }
}