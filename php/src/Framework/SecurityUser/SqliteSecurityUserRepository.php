<?php
declare(strict_types=1);

namespace Demo\App\Framework\SecurityUser;

use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\Database\SqliteConnection;

final class SqliteSecurityUserRepository implements SecurityUserRepository
{
    private DatabaseConnection $dbConnection;
    public function __construct(SqliteConnection $connection)
    {
        $this->dbConnection = $connection;
    }
    
    public function findUserById(string $id): ?SecurityUser
    {
        $result = $this->dbConnection->query(sprintf('SELECT * FROM users WHERE id = \'%s\'', $id));
        if(!$result) {
            return null;
        }

        $row = $result[0];

        return new SecurityUser(
            $row['id'], $row['email'], $row['password'], $row['role'], $row['status']
        );
    }
}
