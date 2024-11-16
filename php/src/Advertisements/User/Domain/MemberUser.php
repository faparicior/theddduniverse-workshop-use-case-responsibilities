<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain;

use Demo\App\Advertisements\Shared\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\ValueObjects\MemberNumber;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserStatus;

class MemberUser extends UserBase
{
    private MemberNumber $memberNumber;
    private CivicCenterId $civicCenterId;

    /** @throws InvalidUserException */
    protected function __construct(UserId $id, Email $email, Role $role, MemberNumber $memberNumber, CivicCenterId $civicCenterId, UserStatus $status)
    {
        if ($role !== Role::MEMBER) {
            throw InvalidUserException::build('Invalid role for member user');
        }

        $this->memberNumber = $memberNumber;
        $this->civicCenterId = $civicCenterId;

        parent::__construct($id, $email, $role, $status);
    }

    /**
     * @throws InvalidUserException
     */
    public static function signUp(UserId $id, Email $email, Password $password, Role $role, MemberNumber $memberNumber, CivicCenterId $civicCenterId): MemberUser
    {
        $member = new self($id, $email, $role, $memberNumber, $civicCenterId, UserStatus::ENABLED);
        $member->password = $password;

        return $member;
    }

    public function disable(): void
    {
        $this->status = UserStatus::DISABLED;
    }

    public function enable(): void
    {
        $this->status = UserStatus::ENABLED;
    }

    /**
     * @throws InvalidUserException
     */
    public static function fromDatabase(UserId $id, Email $email, Role $role, MemberNumber $memberNumber, CivicCenterId $civicCenterId, UserStatus $status): MemberUser
    {
        return new self($id, $email, $role, $memberNumber, $civicCenterId, $status);
    }

    public function memberNumber(): MemberNumber
    {
        return $this->memberNumber;
    }

    public function civicCenterId(): CivicCenterId
    {
        return $this->civicCenterId;
    }
}
