<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\EnableAdvertisement;

use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\Services\SecurityService;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\MemberDoesNotExistsException;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Exception;

final class EnableAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository $advertisementRepository,
        private UserRepository $userRepository,
        private SecurityService $securityService,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(EnableAdvertisementCommand $command): void
    {
        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->advertisementId));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->advertisementId);
        }

        $this->securityService->verifyAdminUserCanManageAdvertisement(
            new UserId($command->securityUserId),
            $advertisement,
        );

        $member = $this->userRepository->findMemberById($advertisement->memberId());

        if (null === $member) {
            throw MemberDoesNotExistsException::build();
        }

        $activeAdvertisements = $this->advertisementRepository->activeAdvertisementsByMember($member);

        if ($activeAdvertisements->value() >= 3) {
            throw new Exception('Member has 3 active advertisements');
        }

        $advertisement->enable();

        $this->advertisementRepository->save($advertisement);
    }
}
