import {UserBase} from "./UserBase";
import {UserId} from "../../shared/domain/value-object/UserId";
import {Email} from "../../shared/domain/value-object/Email";
import {Role} from "./value-object/Role";
import {UserStatus} from "./value-object/UserStatus";
import {InvalidUserException} from "./exceptions/InvalidUserException";

export class SupervisorUser extends UserBase {
    protected constructor(id: UserId, email: Email, role: Role, status: UserStatus) {
        if (role !== Role.SUPERVISOR) {
            throw InvalidUserException.build('Invalid role for supervisor user');
        }
        super(id, email, role, status);
    }

    public static fromDatabase(id: UserId, email: Email, role: Role, status: UserStatus): SupervisorUser {
        return new SupervisorUser(id, email, role, status);
    }
}