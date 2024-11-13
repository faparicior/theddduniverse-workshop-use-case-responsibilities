<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain\ValueObjects;

enum AdvertisementStatus
{
    case ACTIVE;
    case DISABLED;

    public static function fromString(string $role): self
    {
        return match ($role) {
            'active' => self::ACTIVE,
            'disabled' => self::DISABLED,
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::ACTIVE => 'active',
            self::DISABLED => 'disabled',
        };
    }
}
