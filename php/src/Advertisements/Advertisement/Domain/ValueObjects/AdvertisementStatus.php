<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain\ValueObjects;

enum AdvertisementStatus
{
    case ENABLED;
    case DISABLED;

    public static function fromString(string $role): self
    {
        return match ($role) {
            'enabled' => self::ENABLED,
            'disabled' => self::DISABLED,
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::ENABLED => 'enabled',
            self::DISABLED => 'disabled',
        };
    }
}
