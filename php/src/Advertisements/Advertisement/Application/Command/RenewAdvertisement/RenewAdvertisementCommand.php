<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\RenewAdvertisement;

final readonly class RenewAdvertisementCommand
{
    public function __construct(
        public string $id,
        public string $password,
    ){}
}
