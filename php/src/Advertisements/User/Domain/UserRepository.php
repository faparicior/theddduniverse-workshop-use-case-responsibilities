<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain;

use Demo\App\Advertisements\User\Domain\ValueObjects\UserId;

interface UserRepository
{
    public function findAdminById(UserId $id): ?UserBase;
}