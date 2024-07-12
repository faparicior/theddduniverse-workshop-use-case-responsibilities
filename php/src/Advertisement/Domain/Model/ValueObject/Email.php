<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain\Model\ValueObject;

use Demo\App\Advertisement\Domain\Exceptions\InvalidEmailException;

final readonly class Email
{
    /**
     * @throws InvalidEmailException
     */
    public function __construct(private string $value)
    {
        if (!$this->validate($value)) {
            throw InvalidEmailException::withEmail($this->value);
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    private function validate(string $value): string|bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
