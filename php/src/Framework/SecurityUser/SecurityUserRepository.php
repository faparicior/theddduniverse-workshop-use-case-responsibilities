<?php
declare(strict_types=1);

namespace Demo\App\Framework\SecurityUser;

interface SecurityUserRepository
{
    public function findUserById(string $id): ?SecurityUser;
}
