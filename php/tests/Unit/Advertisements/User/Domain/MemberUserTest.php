<?php
declare(strict_types=1);

namespace Tests\Demo\App\Unit\Advertisements\User\Domain;

use Demo\App\Advertisements\Shared\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\MemberUser;
use Demo\App\Advertisements\User\Domain\ValueObjects\MemberNumber;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserStatus;
use PHPUnit\Framework\TestCase;

class MemberUserTest extends TestCase
{
    private const string ID = '6fa00b21-2930-483e-b610-d6b0e5b19b29';
    private const string CIVIC_CENTER_ID = '54500b21-2930-483e-b610-d6b0e5b19b29';
    private const string EMAIL = 'test@test.com';
    private const string PASSWORD = 'password';
    private const string ADMIN_ROLE = 'admin';
    private const string MEMBER_ROLE = 'member';
    private const string MEMBER_NUMBER = '123456';

    public function testShouldCreateAMemberUserDatabase()
    {
        $userId = new UserId(self::ID);
        $civicCenterId = new CivicCenterId(self::CIVIC_CENTER_ID);
        $email = new Email(self::EMAIL);
        $role = Role::fromString(self::MEMBER_ROLE);
        $memberNumber = new MemberNumber(self::MEMBER_NUMBER);
        $status = UserStatus::ENABLED;

        $user = MemberUser::fromDatabase(
            $userId,
            $email,
            $role,
            $memberNumber,
            $civicCenterId,
            $status,
        );

        self::assertInstanceOf(MemberUser::class, $user);
        $this->assertEquals(self::ID, $user->id()->value());
        $this->assertEquals(self::EMAIL, $user->email()->value());
        $this->assertEquals(self::MEMBER_ROLE, $user->role()->value());
        $this->assertEquals(self::MEMBER_NUMBER, $user->memberNumber()->value());
    }

    public function testShouldCreateAMemberUserAsSignUp()
    {
        $userId = new UserId(self::ID);
        $civicCenterId = new CivicCenterId(self::CIVIC_CENTER_ID);
        $email = new Email(self::EMAIL);
        $password = Password::fromPlainPassword(self::PASSWORD);
        $role = Role::fromString(self::MEMBER_ROLE);
        $memberNumber = new MemberNumber(self::MEMBER_NUMBER);

        $user = MemberUser::signUp(
            $userId,
            $email,
            $password,
            $role,
            $memberNumber,
            $civicCenterId,
        );

        self::assertInstanceOf(MemberUser::class, $user);
        $this->assertEquals(self::ID, $user->id()->value());
        $this->assertEquals(self::EMAIL, $user->email()->value());
        $this->assertEquals(self::MEMBER_ROLE, $user->role()->value());
        $this->assertEquals(self::MEMBER_NUMBER, $user->memberNumber()->value());
    }

    public function testShouldFailCreatingAMemberUserWithAdminRole()
    {
        $userId = new UserId(self::ID);
        $civicCenterId = new CivicCenterId(self::CIVIC_CENTER_ID);
        $email = new Email(self::EMAIL);
        $password = Password::fromPlainPassword(self::PASSWORD);
        $role = Role::fromString(self::ADMIN_ROLE);
        $memberNumber = new MemberNumber(self::MEMBER_NUMBER);

        $this->expectException(InvalidUserException::class);

        MemberUser::signUp(
            $userId,
            $email,
            $password,
            $role,
            $memberNumber,
            $civicCenterId,
        );
    }
}
