<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\UpdateAdvertisement;

final readonly class UpdateAdvertisementCommand
{
    public function __construct(
        public string $securityUserId,
        public string $securityUserRole,
        public string $id,
        public string $description,
        public string $email,
        public string $password,
    ){}
}
