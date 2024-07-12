<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Domain;

use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;

interface AdvertisementRepository
{
    public function save(Advertisement $advertisement): void;

    public function findById(AdvertisementId $id): ?Advertisement;
}
