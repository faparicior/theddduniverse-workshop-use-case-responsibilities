<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain\ValueObjects;

enum AdvertisementApprovalStatus
{
    case PENDING_FOR_APPROVAL;
    case APPROVED;

    public static function fromString(string $role): self
    {
        return match ($role) {
            'pending_for_approval' => self::PENDING_FOR_APPROVAL,
            'approved' => self::APPROVED,
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::PENDING_FOR_APPROVAL => 'pending_for_approval',
            self::APPROVED => 'approved',
        };
    }
}
