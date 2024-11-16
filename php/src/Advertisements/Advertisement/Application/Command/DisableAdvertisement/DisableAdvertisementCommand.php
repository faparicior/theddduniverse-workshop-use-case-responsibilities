<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement;

final readonly class DisableAdvertisementCommand
{
    public function __construct(
        public string $id,
        public string $password,
    ){}
}
