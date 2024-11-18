<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\PublishAdvertisement;

use Demo\App\Advertisements\Advertisement\Domain\Advertisement;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementAlreadyExistsException;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementDate;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\Description;
use Demo\App\Advertisements\Shared\ValueObjects\CivicCenterId;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\UserNotFoundException;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Exception;

final class PublishAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository $advertisementRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * @throws Exception
     */
    public function execute(PublishAdvertisementCommand $command): void
    {
        $memberUser = $this->userRepository->findMemberById(new UserId($command->securityUserId));
        if (!$memberUser) {
            throw UserNotFoundException::asMember();
        }

        if ($this->advertisementRepository->findById(new AdvertisementId($command->id))) {
            throw AdvertisementAlreadyExistsException::withId($command->id);
        }

        $activeAdvertisements = $this->advertisementRepository->activeAdvertisementsByMember($memberUser);

        if ($activeAdvertisements->value() >= 3) {
            throw new Exception('Member has 3 active advertisements');
        }

        $advertisement = new Advertisement(
            new AdvertisementId($command->id),
            new Description($command->description),
            new Email($command->email),
            Password::fromPlainPassword($command->password),
            new AdvertisementDate(new \DateTime()),
            new CivicCenterId($command->civicCenterId),
            new UserId($command->memberNumber),
        );

        $this->advertisementRepository->save($advertisement);
    }
}
