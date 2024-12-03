<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Domain\Services;

use Demo\App\Advertisements\Advertisement\Domain\Advertisement;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\AdminWithIncorrectCivicCenterException;
use Demo\App\Advertisements\User\Domain\Exceptions\UnauthorizedUserException;
use Demo\App\Advertisements\User\Domain\Exceptions\UserNotFoundException;
use Demo\App\Advertisements\User\Domain\UserRepository;

class AdvertisementSecurityService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    /**
     * @throws UserNotFoundException
     * @throws AdminWithIncorrectCivicCenterException
     */
    public function verifyAdminUserCanManageAdvertisement(UserId $securityUserId, Advertisement $advertisement): void
    {
        $adminUser = $this->userRepository->findAdminById($securityUserId);
        if (!$adminUser) {
            throw UserNotFoundException::asAdmin();
        }

        if (!$adminUser->civicCenterId()->equals($advertisement->civicCenterId())) {
            throw AdminWithIncorrectCivicCenterException::differentCivicCenterFromMember();
        }
    }

    /**
     * @throws UnauthorizedUserException
     * @throws UserNotFoundException
     */
    public function verifyMemberUserCanManageAdvertisement(UserId $securityUserId, Advertisement $advertisement): void
    {
        $memberUser = $this->userRepository->findMemberByIdOrFail($securityUserId);
        if (!$memberUser) {
            throw UserNotFoundException::asMember();
        }

        if (!$advertisement->memberId()->equals($securityUserId)) {
            throw UnauthorizedUserException::build();
        }
    }
}
