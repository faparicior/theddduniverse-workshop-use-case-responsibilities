<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement;

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
        // TODO: Implement user security

        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->advertisementId));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->advertisementId);
        }

        $advertisement->disable();

        $this->advertisementRepository->save($advertisement);
    }
}
