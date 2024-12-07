<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\DeleteAdvertisement;

use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\Domain\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\MemberDoesNotExistsException;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Exception;

final class DeleteAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository $advertisementRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * @throws Exception
     */
    public function execute(DeleteAdvertisementCommand $command): void
    {
        // TODO: Find the bug in the following code
        $member = $this->userRepository->findMemberById(new UserId($command->securityUserId));

        if (null === $member) {
            throw MemberDoesNotExistsException::build();
        }

        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->advertisementId));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->advertisementId);
        }

        $this->advertisementRepository->delete($advertisement);
    }
}
