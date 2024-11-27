import { UserRepository } from '../../domain/UserRepository';
import { AdminUser } from '../../domain/AdminUser';
import { MemberUser } from '../../domain/MemberUser';
import { DatabaseConnection } from '../../../../framework/database/DatabaseConnection';
import { UserId } from '../../../shared/domain/value-object/UserId';
import { Email } from '../../../shared/domain/value-object/Email';
import { CivicCenterId } from '../../../shared/domain/value-object/CivicCenterId';
import {Role} from "../../domain/value-object/Role";
import {UserStatus} from "../../domain/value-object/UserStatus";
import {MemberNumber} from "../../domain/value-object/MemberNumber";

export class SqliteUserRepository implements UserRepository {
    constructor(private dbConnection: DatabaseConnection) {}

    async findAdminById(id: UserId): Promise<AdminUser | null> {
        const result = await this.dbConnection.query(`SELECT * FROM users WHERE id = ?`, [id.value()]);
        if (!result || result.length < 1) {
            return null;
        }

        const row = result[0] as any;
        if (row.role === 'admin') {
            return AdminUser.fromDatabase(
                new UserId(row.id),
                new Email(row.email),
                Role.ADMIN,
                new CivicCenterId(row.civic_center_id),
                UserStatus.fromString(row.status)
            );
        }

        return null;
    }

    async findMemberById(id: UserId): Promise<MemberUser | null> {
        const result = await this.dbConnection.query(`SELECT * FROM users WHERE id = ?`, [id.value()]);
        if (!result || result.length < 1) {
            return null;
        }

        const row = result[0] as any;
        if (row.role === 'member') {
            return MemberUser.fromDatabase(
                new UserId(row.id),
                new Email(row.email),
                Role.MEMBER,
                new MemberNumber(row.member_number),
                new CivicCenterId(row.civic_center_id),
                UserStatus.fromString(row.status)
            );
        }

        return null;
    }

    async findAdminOrMemberById(id: UserId): Promise<AdminUser | MemberUser | null> {
        const result = await this.dbConnection.query(`SELECT * FROM users WHERE id = ?`, [id.value()]);
        if (!result || result.length < 1) {
            return null;
        }

        const row = result[0] as any;
        if (row.role === 'admin') {
            return AdminUser.fromDatabase(
                new UserId(row.id),
                new Email(row.email),
                Role.ADMIN,
                new CivicCenterId(row.civic_center_id),
                UserStatus.fromString(row.status)
            );
        }

        if (row.role === 'member') {
            return MemberUser.fromDatabase(
                new UserId(row.id),
                new Email(row.email),
                Role.MEMBER,
                new MemberNumber(row.member_number),
                new CivicCenterId(row.civic_center_id),
                UserStatus.fromString(row.status)
            );
        }

        return null;
    }

    async saveMember(member: MemberUser): Promise<void> {
        if (this.isASignUp(member)) {
            await this.dbConnection.execute(
                `INSERT INTO users (id, email, password, role, member_number, civic_center_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)
        ON CONFLICT(id) DO UPDATE SET email = excluded.email, role = excluded.role, member_number = excluded.member_number, civic_center_id = excluded.civic_center_id, status = excluded.status`,
                [
                    member.id().value(),
                    member.email().value(),
                    member.password()?.value(),
                    member.role().value(),
                    member.memberNumber().value(),
                    member.civicCenterId().value(),
                    member.status().value()
                ]
            );
            return;
        }

        await this.dbConnection.execute(
            `INSERT INTO users (id, email, password, role, member_number, civic_center_id) VALUES (?, ?, ?, ?, ?, ?)
      ON CONFLICT(id) DO UPDATE SET email = excluded.email, password = excluded.password, role = excluded.role, member_number = excluded.member_number, civic_center_id = excluded.civic_center_id`,
            [
                member.id().value(),
                member.email().value(),
                member.password()?.value(),
                member.role().value(),
                member.memberNumber().value(),
                member.civicCenterId().value()
            ]
        );
    }

    private isASignUp(member: MemberUser): boolean {
        return member.password() == null;
    }
}