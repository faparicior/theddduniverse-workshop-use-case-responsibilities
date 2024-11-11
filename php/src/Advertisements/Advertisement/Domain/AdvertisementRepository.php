<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain;

use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;

interface AdvertisementRepository
{
    public function save(Advertisement $advertisement): void;

    public function findById(AdvertisementId $id): ?Advertisement;
}
