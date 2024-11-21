<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;

final class UnauthorizedUserException extends DomainException
{
    private const string USER_UNAUTHORIZED = 'User unauthorized';

    private function __construct(string $message )
    {
        parent::__construct($message);
    }

    public static function build(): self
    {
        return new self(self::USER_UNAUTHORIZED);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
