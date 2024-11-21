<?php
declare(strict_types=1);

namespace Demo\App\Framework\Database;

class SqliteTransactionManager implements TransactionManager
{
    private DatabaseConnection $dbConnection;

    public function __construct(DatabaseConnection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function beginTransaction(): void
    {
        $this->dbConnection->execute('BEGIN TRANSACTION;');
    }

    public function commit(): void
    {
        $this->dbConnection->execute('COMMIT;');
    }

    public function rollback(): void
    {
        $this->dbConnection->execute('ROLLBACK;');
    }
}