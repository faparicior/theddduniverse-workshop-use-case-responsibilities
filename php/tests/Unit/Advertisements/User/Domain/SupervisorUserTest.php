<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisements\User\Domain;

use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\SupervisorUser;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserStatus;
use PHPUnit\Framework\TestCase;

class SupervisorUserTest extends TestCase
{
    private const string ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29';
    private const string EMAIL = 'test@test.com';
    private const string SUPERVISOR_ROLE = 'supervisor';
    private const string MEMBER_ROLE = 'member';

    public function testShouldCreateASupervisorUserDatabase()
    {
        $userId = new UserId(self::ID);
        $email = new Email(self::EMAIL);
        $role = Role::fromString(self::SUPERVISOR_ROLE);
        $status = UserStatus::ENABLED;

        $user = SupervisorUser::fromDatabase(
            $userId,
            $email,
            $role,
            $status,
        );

        self::assertInstanceOf(SupervisorUser::class, $user);
        $this->assertEquals(self::ID, $user->id()->value());
        $this->assertEquals(self::EMAIL, $user->email()->value());
        $this->assertEquals(self::SUPERVISOR_ROLE, $user->role()->value());
    }

    public function testShouldFailCreatingASupervisorUserWithMemberRole()
    {
        $userId = new UserId(self::ID);
        $email = new Email(self::EMAIL);
        $role = Role::fromString(self::MEMBER_ROLE);
        $status = UserStatus::ENABLED;

        $this->expectException(InvalidUserException::class);

        SupervisorUser::fromDatabase(
            $userId,
            $email,
            $role,
            $status,
        );
    }
}
