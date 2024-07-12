<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Demo\App\Advertisement\Domain\Exceptions\DescriptionEmptyException;
use Demo\App\Advertisement\Domain\Exceptions\DescriptionTooLongException;

final class Description
{
    public function __construct(private string $value)
    {
        $this->validate($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @throws DescriptionTooLongException
     * @throws DescriptionEmptyException
     */
    private function validate(string $value): void
    {
        if (mb_strlen($value) === 0) {
            throw DescriptionEmptyException::build();
        }

        if (mb_strlen($value) > 200) {
            throw DescriptionTooLongException::withLongitudeMessage($this->value);
        }
    }
}
