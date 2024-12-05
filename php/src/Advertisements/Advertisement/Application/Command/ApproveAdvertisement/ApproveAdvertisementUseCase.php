<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\ApproveAdvertisement;

use Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement\DisableAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Command\EnableAdvertisement\EnableAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\Services\AdvertisementSecurityService;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\AdminWithIncorrectCivicCenterException;
use Demo\App\Advertisements\User\Domain\Exceptions\MemberDoesNotExistsException;
use Demo\App\Advertisements\User\Domain\Exceptions\UserNotFoundException;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Exception;

final class ApproveAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository      $advertisementRepository,
        private AdvertisementSecurityService $securityService,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(ApproveAdvertisementCommand $command): void
    {
        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->advertisementId));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->advertisementId);
        }

        $this->securityService->verifyAdminUserCanManageAdvertisement(
            new UserId($command->securityUserId),
            $advertisement,
        );

        $advertisement->approve();

        $this->advertisementRepository->save($advertisement);
    }
}
