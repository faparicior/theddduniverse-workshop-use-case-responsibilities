<?php
declare(strict_types=1);

namespace Demo\App\Framework\SecurityUser;

final class SecurityUser
{
    public function __construct(
        private string $id,
        private string $email,
        private string $password,
        private string $role,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function role(): string
    {
        return $this->role;
    }
}
