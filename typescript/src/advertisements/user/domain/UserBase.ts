import {Password} from "../../shared/domain/value-object/Password";
import {UserId} from "../../shared/domain/value-object/UserId";
import {Email} from "../../shared/domain/value-object/Email";
import {Role} from "./value-object/Role";
import {UserStatus} from "./value-object/UserStatus";

export abstract class UserBase {
    protected password: Password | null = null;

    protected constructor(
        protected readonly id: UserId,
        protected email: Email,
        protected role: Role,
        protected status: UserStatus
    ) {}

    public getId(): UserId {
        return this.id;
    }

    public getEmail(): Email {
        return this.email;
    }

    public getPassword(): Password | null {
        return this.password;
    }

    public getRole(): Role {
        return this.role;
    }

    public getStatus(): UserStatus {
        return this.status;
    }
}