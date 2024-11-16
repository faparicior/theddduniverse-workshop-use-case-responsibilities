<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain;

use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserStatus;

class SupervisorUser extends UserBase
{
    /** @throws InvalidUserException */
    protected function __construct(UserId $id, Email $email, Role $role, UserStatus $status)
    {
        if ($role !== Role::SUPERVISOR) {
            throw InvalidUserException::build('Invalid role for supervisor user');
        }
        parent::__construct($id, $email, $role, $status);
    }

    /**
     * @throws InvalidUserException
     */
    public static function fromDatabase(UserId $id, Email $email, Role $role, UserStatus $status): SupervisorUser
    {
        return new self($id, $email, $role, $status);
    }
}
