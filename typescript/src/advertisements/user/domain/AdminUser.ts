import { UserBase } from "./UserBase";
import { UserId } from "../../shared/domain/value-object/UserId";
import { Email } from "../../shared/domain/value-object/Email";
import { Role } from "./value-object/Role";
import { UserStatus } from "./value-object/UserStatus";
import { InvalidUserException } from "./exceptions/InvalidUserException";
import { CivicCenterId } from "../../shared/domain/value-object/CivicCenterId";

export class AdminUser extends UserBase {
    private _civicCenterId: CivicCenterId;

    /** @throws InvalidUserException */
    protected constructor(id: UserId, email: Email, role: Role, civicCenterId: CivicCenterId, status: UserStatus) {
        if (role !== Role.ADMIN) {
            throw InvalidUserException.build('Invalid role for admin user');
        }

        super(id, email, role, status);
        this._civicCenterId = civicCenterId;
    }

    /** @throws InvalidUserException */
    public static fromDatabase(id: UserId, email: Email, role: Role, civicCenterId: CivicCenterId, status: UserStatus): AdminUser {
        return new AdminUser(id, email, role, civicCenterId, status);
    }

    public civicCenterId(): CivicCenterId {
        return this._civicCenterId;
    }
}
