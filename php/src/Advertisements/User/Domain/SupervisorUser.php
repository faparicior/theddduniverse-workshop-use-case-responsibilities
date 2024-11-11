<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain;

use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserId;

class SupervisorUser extends UserBase
{
    /** @throws InvalidUserException */
    public function __construct(UserId $id, Email $email, Password $password, Role $role)
    {
        if ($role !== Role::SUPERVISOR) {
            throw InvalidUserException::build('Invalid role for supervisor user');
        }
        parent::__construct($id, $email, $password, $role);
    }
}
