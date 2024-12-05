package framework.database

class SqliteTransactionManager(private val dbConnection: DatabaseConnection) : TransactionManager {

    override fun beginTransaction() {
        dbConnection.execute("BEGIN TRANSACTION;")
    }

    override fun commit() {
        dbConnection.execute("COMMIT;")
    }

    override fun rollback() {
        dbConnection.execute("ROLLBACK;")
    }
}