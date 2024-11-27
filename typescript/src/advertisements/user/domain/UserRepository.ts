import { AdminUser } from './AdminUser';
import { MemberUser } from './MemberUser';
import {UserId} from "../../shared/domain/value-object/UserId";

export interface UserRepository {
    // findAdminById(id: UserId): Promise<AdminUser | null>;
    findMemberById(id: UserId): Promise<MemberUser | null>;
    // findAdminOrMemberById(id: UserId): Promise<AdminUser | MemberUser | null>;
    // saveMember(member: MemberUser): Promise<void>;
}
