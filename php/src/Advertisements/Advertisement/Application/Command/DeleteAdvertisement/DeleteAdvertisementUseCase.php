<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\DeleteAdvertisement;

use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\Services\SecurityService;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Exception;

final class DeleteAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository $advertisementRepository,
        private SecurityService $securityService,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(DeleteAdvertisementCommand $command): void
    {
        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->advertisementId));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->advertisementId);
        }

        $memberId = new UserId($command->securityUserId);

        $this->securityService->verifyMemberUserCanManageAdvertisement(
            $memberId,
            $advertisement,
        );

        $this->advertisementRepository->delete($advertisement);
    }
}
