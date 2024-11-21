<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain;

use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\ActiveAdvertisements;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Advertisement\Infrastructure\Persistence\SqliteAdvertisementRepository;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\MemberUser;

interface AdvertisementRepository
{
    public function save(Advertisement $advertisement): void;

    public function findByIdOrFail(AdvertisementId $id): Advertisement;

    public function findByIdOrNull(AdvertisementId $id): ?Advertisement;

    public function activeAdvertisementsByMemberId(UserId $member): ActiveAdvertisements;

    public function delete(Advertisement $advertisement): void;
}
