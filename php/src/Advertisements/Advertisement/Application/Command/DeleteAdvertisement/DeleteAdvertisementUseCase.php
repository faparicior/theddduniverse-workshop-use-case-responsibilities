<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\DeleteAdvertisement;

use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
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
        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->advertisementId));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->advertisementId);
        }

        $member = $this->userRepository->findMemberById($advertisement->memberId());

        if (null === $member) {
            throw MemberDoesNotExistsException::build();
        }

        $activeAdvertisements = $this->advertisementRepository->activeAdvertisementsByMember($member);

        if ($activeAdvertisements->value() >= 3) {
            throw new Exception('Member has 3 active advertisements');
        }

        $this->advertisementRepository->delete($advertisement);
    }
}
