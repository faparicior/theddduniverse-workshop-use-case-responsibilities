<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\User\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;

final class MemberDoesNotExistsException extends DomainException
{
    private const string MEMBER_DOES_NOT_EXISTS_MESSAGE = 'Member does not exists';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(): self
    {
        return new self(self::MEMBER_DOES_NOT_EXISTS_MESSAGE);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
