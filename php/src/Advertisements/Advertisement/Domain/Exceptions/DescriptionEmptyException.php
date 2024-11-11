<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;

final class DescriptionEmptyException extends DomainException
{
    public const string DESCRIPTION_EMPTY_MESSAGE = 'Description empty';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function build(string $message = self::DESCRIPTION_EMPTY_MESSAGE): self
    {
        return new self($message);
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
