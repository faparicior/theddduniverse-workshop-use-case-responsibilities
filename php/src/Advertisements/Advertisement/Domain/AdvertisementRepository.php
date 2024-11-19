<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain;

use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\ActiveAdvertisements;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Advertisement\Infrastructure\Persistence\SqliteAdvertisementRepository;
use Demo\App\Advertisements\User\Domain\MemberUser;

interface AdvertisementRepository
{
    public function save(Advertisement $advertisement): void;

    public function findById(AdvertisementId $id): ?Advertisement;

    public function activeAdvertisementsByMember(MemberUser $member): ActiveAdvertisements;

    public function delete(Advertisement $advertisement): void;
}
