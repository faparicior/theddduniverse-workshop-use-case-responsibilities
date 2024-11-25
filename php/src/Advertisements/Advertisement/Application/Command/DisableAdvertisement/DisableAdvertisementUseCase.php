<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement;

use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\Domain\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\UserNotFoundException;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Exception;

final class DisableAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository $advertisementRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * @throws Exception
     */
    public function execute(DisableAdvertisementCommand $command): void
    {
        if ('admin' === $command->securityUserRole) {
            $adminUser = $this->userRepository->findAdminById(new UserId($command->securityUserId));
            if (!$adminUser) {
                throw UserNotFoundException::asAdmin();
            }
        }

        if ('member' === $command->securityUserRole) {
            $member = $this->userRepository->findMemberById(new UserId($command->securityUserId));
            if (!$member) {
                throw UserNotFoundException::asMember();
            }
        }

        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->advertisementId));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->advertisementId);
        }

        $advertisement->disable();

        $this->advertisementRepository->save($advertisement);
    }
}
