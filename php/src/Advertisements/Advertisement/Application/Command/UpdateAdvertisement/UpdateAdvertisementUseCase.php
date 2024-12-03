<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\UpdateAdvertisement;

use Demo\App\Advertisements\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\Services\AdvertisementSecurityService;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\Description;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Advertisements\User\Domain\Exceptions\UserNotFoundException;
use Demo\App\Advertisements\User\Domain\UserRepository;
use Exception;

final class UpdateAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository      $advertisementRepository,
        private AdvertisementSecurityService $securityService,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(UpdateAdvertisementCommand $command): void
    {
        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->id));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->id);
        }

        $this->securityService->verifyMemberUserCanManageAdvertisement(
            new UserId($command->securityUserId),
            $advertisement,
        );

        if (!$advertisement->password()->isValidatedWith($command->password)) {
            throw InvalidPasswordException::build();
        }

        $advertisement->update(
            new Description($command->description),
            new Email($command->email),
            Password::fromPlainPassword($command->password),
        );

        $this->advertisementRepository->save($advertisement);
    }
}
