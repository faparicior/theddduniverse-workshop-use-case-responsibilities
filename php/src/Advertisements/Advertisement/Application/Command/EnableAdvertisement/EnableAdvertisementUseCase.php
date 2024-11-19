<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\EnableAdvertisement;

use Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement\DisableAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\AdminWithIncorrectCivicCenterException;
use Demo\App\Advertisements\User\Domain\Exceptions\MemberDoesNotExistsException;
use Demo\App\Advertisements\User\Domain\Exceptions\UserNotFoundException;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Exception;

final class EnableAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository $advertisementRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * @throws Exception
     */
    public function execute(EnableAdvertisementCommand $command): void
    {
        $adminUser = null;
        if ($command->securityUserRole === 'admin') {
            $adminUser = $this->userRepository->findAdminById(new UserId($command->securityUserId));
            if (!$adminUser) {
                throw UserNotFoundException::asAdmin();
            }
        }

        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->advertisementId));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->advertisementId);
        }

        $member = $this->userRepository->findMemberById($advertisement->memberId());

        if (null === $member) {
            throw MemberDoesNotExistsException::build();
        }

        if ($adminUser && !$adminUser->civicCenterId()->equals($member->civicCenterId())) {
            throw AdminWithIncorrectCivicCenterException::differentCivicCenterFromMember();
        }

        $activeAdvertisements = $this->advertisementRepository->activeAdvertisementsByMember($member);

        if ($activeAdvertisements->value() >= 3) {
            throw new Exception('Member has 3 active advertisements');
        }

        $advertisement->enable();

        $this->advertisementRepository->save($advertisement);
    }
}
