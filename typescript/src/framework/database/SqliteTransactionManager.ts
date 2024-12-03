import { TransactionManager } from './TransactionManager';
import {DatabaseConnection} from "./DatabaseConnection";

export class SqliteTransactionManager implements TransactionManager {
    private dbConnection: DatabaseConnection;

    constructor(dbConnection: DatabaseConnection) {
        this.dbConnection = dbConnection;
    }

    public async beginTransaction(): Promise<void> {
        await this.dbConnection.execute('BEGIN TRANSACTION;', []);
    }

    public async commit(): Promise<void> {
        await this.dbConnection.execute('COMMIT;', []);
    }

    public async rollback(): Promise<void> {
        await this.dbConnection.execute('ROLLBACK;', []);
    }
}
