import {DatabaseConnection} from "../database/DatabaseConnection";
import {SecurityUserRepository} from "./SecurityUserRepository";
import {SecurityUser} from "./SecurityUser";

export class SqliteSecurityUserRepository implements SecurityUserRepository {
    private dbConnection: DatabaseConnection;

    constructor(connection: DatabaseConnection) {
        this.dbConnection = connection;
    }

    public async findUserById(id: string): Promise<SecurityUser | null> {
        const result = await this.dbConnection.query(
            `SELECT *
                FROM users
                WHERE id = '${id}'`
        );

        if (!result || result.length === 0) {
            return null;
        }

        const row = result[0] as any;

        return new SecurityUser(
            row.id, row.email, row.password, row.role, row.status
        );
    }
}
