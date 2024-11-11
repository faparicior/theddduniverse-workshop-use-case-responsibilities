<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Shared\ValueObjects;

use Exception;
use SensitiveParameter;

final readonly class Password
{
    private function __construct(
        #[SensitiveParameter]
        private string $value
    )
    {
    }

    /**
     * @throws Exception
     */
    public static function fromPlainPassword(
        #[SensitiveParameter]
        string $password)
    : self
    {
        $result = password_hash($password, PASSWORD_ARGON2I);
        if(null === $result || false === $result) {
            throw new Exception("Problem hashing password");
        }
        return new Password($result);
    }

    public static function fromEncryptedPassword(
        #[SensitiveParameter]
        string $password
    ): self
    {
        return new Password($password);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isValidatedWith(
        #[SensitiveParameter]
        string $password
    ): bool
    {
        $hashSpecs = password_get_info($this->value);

        if(null === $hashSpecs['algo']) {
            return $this->value === md5($password);
        }

        return password_verify($password, $this->value);
    }
}
