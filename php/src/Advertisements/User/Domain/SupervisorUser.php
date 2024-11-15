<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain;

use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;

class SupervisorUser extends UserBase
{
    /** @throws InvalidUserException */
    protected function __construct(UserId $id, Email $email, Role $role)
    {
        if ($role !== Role::SUPERVISOR) {
            throw InvalidUserException::build('Invalid role for supervisor user');
        }
        parent::__construct($id, $email, $role);
    }

    /**
     * @throws InvalidUserException
     */
    public static function fromDatabase(UserId $id, Email $email, Role $role): SupervisorUser
    {
        return new self($id, $email, $role);
    }
}
