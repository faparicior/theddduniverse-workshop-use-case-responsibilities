<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\EnableAdvertisement;

use Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement\DisableAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Exception;

final class EnableAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(EnableAdvertisementCommand $command): void
    {
        // TODO: Implement user security

        // TODO:Revise if the member has 3 active advertisements

        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->id));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->id);
        }

        if (!$advertisement->password()->isValidatedWith($command->password)) {
            throw InvalidPasswordException::build();
        }

        $advertisement->enable();

        $this->advertisementRepository->save($advertisement);
    }
}
