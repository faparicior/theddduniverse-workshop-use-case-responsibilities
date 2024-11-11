<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain;

use Demo\App\Advertisements\CivicCenter\Domain\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\ValueObjects\MemberNumber;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserId;

class MemberUser extends UserBase
{
    private MemberNumber $memberNumber;
    private CivicCenterId $civicCenterId;

    /** @throws InvalidUserException */
    public function __construct(UserId $id, Email $email, Password $password, Role $role, MemberNumber $memberNumber, CivicCenterId $civicCenterId)
    {
        if ($role !== Role::MEMBER) {
            throw InvalidUserException::build('Invalid role for member user');
        }

        $this->memberNumber = $memberNumber;
        $this->civicCenterId = $civicCenterId;

        parent::__construct($id, $email, $password, $role);
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
