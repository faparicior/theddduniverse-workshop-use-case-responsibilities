import {SecurityUser} from "./SecurityUser";

export interface SecurityUserRepository {
    findUserById(id: string): Promise<SecurityUser | null> ;
}
