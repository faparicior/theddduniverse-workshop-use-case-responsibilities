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
use Demo\App\Advertisements\User\Domain\MemberUser;
use Demo\App\Advertisements\User\Domain\UserBase;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Demo\App\Advertisements\User\Domain\ValueObjects\MemberNumber;
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
    public function findAdminById(UserId $id): ?AdminUser
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

    /**
     * @throws InvalidEmailException
     * @throws InvalidUniqueIdentifierException
     * @throws InvalidUserException
     */
    public function findMemberById(UserId $id): ?MemberUser
    {
        $result = $this->dbConnection->query(sprintf('SELECT * FROM users WHERE id = \'%s\'', $id->value()));
        if(!$result) {
            return null;
        }

        $row = $result[0];

        if ($row['role'] === 'member') {
            return new MemberUser(
                new UserId($row['id']),
                new Email($row['email']),
                Password::fromEncryptedPassword($row['password']),
                Role::MEMBER,
                new MemberNumber($row['member_number']),
                new CivicCenterId($row['civic_center_id']),
            );
        }

        return null;
    }

    public function saveMember(MemberUser $member): void
    {
        $this->dbConnection->execute(sprintf('
            INSERT INTO users (id, email, password, role, member_number, civic_center_id) VALUES (\'%1$s\', \'%2$s\', \'%3$s\', \'%4$s\', \'%5$s\', \'%6$s\') 
            ON CONFLICT(id) DO UPDATE SET email = \'%2$s\', password = \'%3$s\', role = \'%4$s\', member_number = \'%5$s\', civic_center_id = \'%6$s\';',
                $member->id()->value(),
                $member->email()->value(),
                $member->password()->value(),
                $member->role()->value(),
                $member->memberNumber()->value(),
                $member->civicCenterId()->value(),
            )
        );
    }
}