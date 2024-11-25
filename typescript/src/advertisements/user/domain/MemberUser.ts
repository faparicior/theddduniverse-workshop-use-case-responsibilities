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
    private memberNumber: MemberNumber;
    private civicCenterId: CivicCenterId;

    /** @throws InvalidUserException */
    protected constructor(id: UserId, email: Email, role: Role, memberNumber: MemberNumber, civicCenterId: CivicCenterId, status: UserStatus) {
        if (role !== Role.MEMBER) {
            throw InvalidUserException.build('Invalid role for member user');
        }

        super(id, email, role, status);
        this.memberNumber = memberNumber;
        this.civicCenterId = civicCenterId;
    }

    /** @throws InvalidUserException */
    public static signUp(id: UserId, email: Email, password: Password, role: Role, memberNumber: MemberNumber, civicCenterId: CivicCenterId): MemberUser {
        const member = new MemberUser(id, email, role, memberNumber, civicCenterId, UserStatus.ENABLED);
        member.password = password;
        return member;
    }

    public disable(): void {
        this.status = UserStatus.DISABLED;
    }

    public enable(): void {
        this.status = UserStatus.ENABLED;
    }

    /** @throws InvalidUserException */
    public static fromDatabase(id: UserId, email: Email, role: Role, memberNumber: MemberNumber, civicCenterId: CivicCenterId, status: UserStatus): MemberUser {
        return new MemberUser(id, email, role, memberNumber, civicCenterId, status);
    }

    public getMemberNumber(): MemberNumber {
        return this.memberNumber;
    }

    public getCivicCenterId(): CivicCenterId {
        return this.civicCenterId;
    }
}
