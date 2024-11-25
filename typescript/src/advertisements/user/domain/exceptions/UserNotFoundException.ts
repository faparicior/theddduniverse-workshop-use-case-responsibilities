import { DomainException } from "../../../../common/domain/DomainException";

export class UserNotFoundException extends DomainException {
    private static readonly ADMIN_USER_NOT_FOUND_MESSAGE = 'Admin user not found';
    private static readonly MEMBER_USER_NOT_FOUND_MESSAGE = 'Member user not found';

    private constructor(message: string) {
        super(message);
    }

    public static asAdmin(): UserNotFoundException {
        return new UserNotFoundException(UserNotFoundException.ADMIN_USER_NOT_FOUND_MESSAGE);
    }

    public static asMember(): UserNotFoundException {
        return new UserNotFoundException(UserNotFoundException.MEMBER_USER_NOT_FOUND_MESSAGE);
    }

    public getMessage(): string {
        return this.message;
    }
}
