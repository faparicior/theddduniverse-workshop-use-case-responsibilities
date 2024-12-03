import {Password} from "../../shared/domain/value-object/Password";
import {UserId} from "../../shared/domain/value-object/UserId";
import {Email} from "../../shared/domain/value-object/Email";
import {Role} from "./value-object/Role";
import {UserStatus} from "./value-object/UserStatus";

export abstract class UserBase {
    protected _password: Password | null = null;

    protected constructor(
        protected readonly _id: UserId,
        protected _email: Email,
        protected _role: Role,
        protected _status: UserStatus
    ) {}

    public id(): UserId {
        return this._id;
    }

    public email(): Email {
        return this._email;
    }

    public password(): Password | null {
        return this._password;
    }

    public role(): Role {
        return this._role;
    }

    public status(): UserStatus {
        return this._status;
    }
}