<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;

final class UserNotFoundException extends DomainException
{
    private const string ADMIN_USER_NOT_FOUND_MESSAGE = 'Admin user not found';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function asAdmin(): self
    {
        return new self(self::ADMIN_USER_NOT_FOUND_MESSAGE);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
