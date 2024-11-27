import { UserBase } from "./UserBase";
import { UserId } from "../../shared/domain/value-object/UserId";
import { Email } from "../../shared/domain/value-object/Email";
import { Role } from "./value-object/Role";
import { UserStatus } from "./value-object/UserStatus";
import { InvalidUserException } from "./exceptions/InvalidUserException";
import { MemberNumber } from "./value-object/MemberNumber";
import { Password } from "../../shared/domain/value-object/Password";
import {CivicCenterId} from "../../shared/domain/value-object/CivicCenterId";

export class MemberUser extends UserBase {
    private _memberNumber: MemberNumber;
    private _civicCenterId: CivicCenterId;

    /** @throws InvalidUserException */
    protected constructor(id: UserId, email: Email, role: Role, memberNumber: MemberNumber, civicCenterId: CivicCenterId, status: UserStatus) {
        if (role !== Role.MEMBER) {
            throw InvalidUserException.build('Invalid role for member user');
        }

        super(id, email, role, status);
        this._memberNumber = memberNumber;
        this._civicCenterId = civicCenterId;
    }

    /** @throws InvalidUserException */
    public static signUp(id: UserId, email: Email, password: Password, role: Role, memberNumber: MemberNumber, civicCenterId: CivicCenterId): MemberUser {
        const member = new MemberUser(id, email, role, memberNumber, civicCenterId, UserStatus.ENABLED);
        member._password = password;
        return member;
    }

    public disable(): void {
        this._status = UserStatus.DISABLED;
    }

    public enable(): void {
        this._status = UserStatus.ENABLED;
    }

    /** @throws InvalidUserException */
    public static fromDatabase(id: UserId, email: Email, role: Role, memberNumber: MemberNumber, civicCenterId: CivicCenterId, status: UserStatus): MemberUser {
        return new MemberUser(id, email, role, memberNumber, civicCenterId, status);
    }

    public memberNumber(): MemberNumber {
        return this._memberNumber;
    }

    public civicCenterId(): CivicCenterId {
        return this._civicCenterId;
    }
}
