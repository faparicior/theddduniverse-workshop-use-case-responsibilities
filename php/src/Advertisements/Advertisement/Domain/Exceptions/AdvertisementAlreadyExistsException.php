<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain\Exceptions;

use Demo\App\Common\Application\ApplicationException;

final class AdvertisementAlreadyExistsException extends ApplicationException
{
    private const string ADVERTISEMENT_WITH_ID_S_ALREADY_EXISTS_MESSAGE = 'Advertisement with id %s already exists';

    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function withId(string $id): self
    {
        return new self(sprintf(self::ADVERTISEMENT_WITH_ID_S_ALREADY_EXISTS_MESSAGE, $id));
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
