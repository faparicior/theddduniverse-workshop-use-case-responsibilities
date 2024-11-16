<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain\ValueObjects;

enum Status
{
    case ACTIVE;
    case INACTIVE;

    public static function fromString(string $status): self
    {
        return match ($status) {
            'active' => self::ACTIVE,
            'inactive' => self::INACTIVE,
            default => throw new \InvalidArgumentException("Invalid status: $status"),
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::ACTIVE => 'active',
            self::INACTIVE => 'inactive',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
