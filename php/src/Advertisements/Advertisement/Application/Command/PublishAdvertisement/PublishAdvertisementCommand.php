<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\PublishAdvertisement;

final readonly class PublishAdvertisementCommand
{
    public function __construct(
        public string $securityUserId,
        public string $securityUserRole,
        public string $id,
        public string $description,
        public string $email,
        public string $password,
        public string $memberNumber,
        public string $civicCenterId,
    ){}
}
