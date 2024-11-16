<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement;

use Demo\App\Advertisements\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Exception;

final class DisableAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(DisableAdvertisementCommand $command): void
    {
        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->id));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->id);
        }

        if (!$advertisement->password()->isValidatedWith($command->password)) {
            throw InvalidPasswordException::build();
        }

        $advertisement->disable();

        $this->advertisementRepository->save($advertisement);
    }
}
