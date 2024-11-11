<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;

final class InvalidUserException extends DomainException
{
    public const string INVALID_USER_MESSAGE = 'Invalid user';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(string $email): self
    {
        return new self(self::INVALID_USER_MESSAGE);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
