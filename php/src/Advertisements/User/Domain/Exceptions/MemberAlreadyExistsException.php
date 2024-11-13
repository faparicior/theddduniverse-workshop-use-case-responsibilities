<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;

final class MemberAlreadyExistsException extends DomainException
{
    private const string MEMBER_EXISTS_MESSAGE = 'Member already exists';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(): self
    {
        return new self(self::MEMBER_EXISTS_MESSAGE);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
