<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain\ValueObjects;

enum UserStatus
{
    case ENABLED;
    case DISABLED;

    public static function fromString(string $status): self
    {
        return match ($status) {
            'enabled' => self::ENABLED,
            'disabled' => self::DISABLED,
            default => throw new \InvalidArgumentException("Invalid status: $status"),
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::ENABLED => 'enabled',
            self::DISABLED => 'disabled',
        };
    }

    public function isEnabled(): bool
    {
        return $this === self::ENABLED;
    }
}
