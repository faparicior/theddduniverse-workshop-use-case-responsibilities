<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain;

use Demo\App\Advertisements\CivicCenter\Domain\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserId;

class AdminUser extends UserBase
{
    private CivicCenterId $civicCenterId;

    /** @throws InvalidUserException */
    public function __construct(UserId $id, Email $email, Password $password, Role $role, CivicCenterId $civicCenterId)
    {
        if ($role !== Role::ADMIN) {
            throw InvalidUserException::build('Invalid role for admin user');
        }

        $this->civicCenterId = $civicCenterId;

        parent::__construct($id, $email, $password, $role);
    }

    public function civicCenterId(): CivicCenterId
    {
        return $this->civicCenterId;
    }
}
