<?php
declare(strict_types=1);

namespace Demo\App\Framework\SecurityUser;

final class SecurityUser
{
    const string STATUS_ACTIVE = 'active';
    const string STATUS_INACTIVE = 'inactive';

    public function __construct(
        private string $id,
        private string $email,
        private string $password,
        private string $role,
        private string $status,
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

    public function status(): string
    {
        return $this->status;
    }
}
