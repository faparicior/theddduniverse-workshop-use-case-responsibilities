<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;

final class AdminWithIncorrectCivicCenterException extends DomainException
{
    private const string ADMIN_DOES_NOT_BELONG_TO_THE_SAME_CIVIC_CENTER = 'Admin does not belong to the same civic center';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function differentCivicCenterFromMember(): self
    {
        return new self(self::ADMIN_DOES_NOT_BELONG_TO_THE_SAME_CIVIC_CENTER);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
