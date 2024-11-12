<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Infrastructure\Persistence;

use Demo\App\Advertisements\Advertisement\Domain\Exceptions\InvalidEmailException;
use Demo\App\Advertisements\CivicCenter\Domain\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\Exceptions\InvalidUniqueIdentifierException;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\User\Domain\AdminUser;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\UserBase;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserId;
use Demo\App\Framework\Database\DatabaseConnection;
use Demo\App\Framework\Database\SqliteConnection;

class SqliteUserRepository implements UserRepository
{
    private DatabaseConnection $dbConnection;
    public function __construct(SqliteConnection $connection)
    {
        $this->dbConnection = $connection;
    }

    /**
     * @throws InvalidEmailException
     * @throws InvalidUniqueIdentifierException
     * @throws InvalidUserException
     */
    public function findAdminById(UserId $id): ?UserBase
    {
        $result = $this->dbConnection->query(sprintf('SELECT * FROM users WHERE id = \'%s\'', $id->value()));
        if(!$result) {
            return null;
        }

        $row = $result[0];

        if ($row['role'] === 'admin') {
            return new AdminUser(
                new UserId($row['id']),
                new Email($row['email']),
                Password::fromEncryptedPassword($row['password']),
                Role::ADMIN,
                new CivicCenterId($row['civic_center_id']),
            );
        }

        return null;
    }
}
