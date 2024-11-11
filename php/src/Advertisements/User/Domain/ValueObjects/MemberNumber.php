<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain\ValueObjects;

class MemberNumber
{
    public function __construct(private string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }
}
