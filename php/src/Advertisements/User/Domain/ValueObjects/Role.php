<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain\ValueObjects;

enum Role
{
    case SUPERVISOR;
    case ADMIN;
    case MEMBER;

    public static function fromString(string $role): self
    {
        return match ($role) {
            'supervisor' => self::SUPERVISOR,
            'admin' => self::ADMIN,
            'member' => self::MEMBER,
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::SUPERVISOR => 'supervisor',
            self::ADMIN => 'admin',
            self::MEMBER => 'member',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
}
