<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\RenewAdvertisement;

use Demo\App\Advertisements\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Exception;

final class RenewAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(RenewAdvertisementCommand $command): void
    {
        // TODO: Implement user security

        $advertisement = $this->advertisementRepository->findByIdOrFail(new AdvertisementId($command->id));

        if (!$advertisement->password()->isValidatedWith($command->password)) {
            throw InvalidPasswordException::build();
        }

        $advertisement->renew(Password::fromPlainPassword($command->password));

        $this->advertisementRepository->save($advertisement);
    }
}
