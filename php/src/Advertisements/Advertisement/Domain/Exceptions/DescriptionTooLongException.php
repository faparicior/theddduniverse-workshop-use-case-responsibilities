<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain\Exceptions;

use Demo\App\Common\Domain\DomainException;

final class DescriptionTooLongException extends DomainException
{
    public const string DESCRIPTION_TOO_LONG_WITH_COUNTER_MESSAGE = 'Description has more than 200 characters: Has %s characters';

    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function withLongitudeMessage(string $description): self
    {
        return new self(sprintf(self::DESCRIPTION_TOO_LONG_WITH_COUNTER_MESSAGE, mb_strlen($description)));
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
