<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain\ValueObjects;

readonly class ActiveAdvertisements
{
    private function __construct(private int $activeAdvertisements)
    {
    }

    public static function fromInt(int $activeAdvertisements): self
    {
        return new self($activeAdvertisements);
    }

    public function value(): int
    {
        return $this->activeAdvertisements;
    }
}