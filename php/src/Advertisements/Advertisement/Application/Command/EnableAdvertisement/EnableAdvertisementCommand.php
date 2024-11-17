<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\EnableAdvertisement;

final readonly class EnableAdvertisementCommand
{
    public function __construct(
        public string $securityUserId,
        public string $securityUserRole,
        public string $advertisementId,
    ){}
}
