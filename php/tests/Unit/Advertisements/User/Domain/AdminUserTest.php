<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisements\User\Domain;

use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\User\Domain\AdminUser;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class AdminUserTest extends TestCase
{
    private const string ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29';
    private const string EMAIL = 'test@test.com';
    private const string PASSWORD = 'password';

    private const string SUPERVISOR_ROLE = 'supervisor';
    private const string ADMIN_ROLE = 'admin';
    private const string MEMBER_ROLE = 'member';

    public function testShouldCreateAnAdminUser()
    {
        $userId = new UserId(self::ID);
        $email = new Email(self::EMAIL);
        $password = Password::fromPlainPassword(self::PASSWORD);
        $role = Role::fromString(self::ADMIN_ROLE);

        $user = new AdminUser(
            $userId,
            $email,
            $password,
            $role,
        );

        self::assertInstanceOf(AdminUser::class, $user);
        $this->assertEquals(self::ID, $user->id()->value());
        $this->assertEquals(self::EMAIL, $user->email()->value());
        $this->assertEquals(self::ADMIN_ROLE, $user->role()->value());
    }

    public function testShouldFailCreatingAnAdminUserWithMemberRole()
    {
        $userId = new UserId(self::ID);
        $email = new Email(self::EMAIL);
        $password = Password::fromPlainPassword(self::PASSWORD);
        $role = Role::fromString(self::MEMBER_ROLE);

        $this->expectException(InvalidUserException::class);

        new AdminUser(
            $userId,
            $email,
            $password,
            $role,
        );
    }
}
