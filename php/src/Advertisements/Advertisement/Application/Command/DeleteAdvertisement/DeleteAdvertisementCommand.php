<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\DeleteAdvertisement;

final readonly class DeleteAdvertisementCommand
{
    public function __construct(
        public string $securityUserId,
        public string $securityUserRole,
        public string $advertisementId,
    ){}
}
