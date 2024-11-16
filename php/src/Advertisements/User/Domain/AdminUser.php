<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain;

use Demo\App\Advertisements\Shared\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\InvalidUserException;
use Demo\App\Advertisements\User\Domain\ValueObjects\Role;
use Demo\App\Advertisements\User\Domain\ValueObjects\UserStatus;

class AdminUser extends UserBase
{
    private CivicCenterId $civicCenterId;

    /** @throws InvalidUserException */
    protected function __construct(UserId $id, Email $email, Role $role, CivicCenterId $civicCenterId, UserStatus $status)
    {
        if ($role !== Role::ADMIN) {
            throw InvalidUserException::build('Invalid role for admin user');
        }

        $this->civicCenterId = $civicCenterId;

        parent::__construct($id, $email, $role, $status);
    }

    /**
     * @throws InvalidUserException
     */
    public static function fromDatabase(UserId $id, Email $email, Role $role, CivicCenterId $civicCenterId, UserStatus $status): AdminUser
    {
        return new self($id, $email, $role, $civicCenterId, $status);
    }

    public function civicCenterId(): CivicCenterId
    {
        return $this->civicCenterId;
    }
}
