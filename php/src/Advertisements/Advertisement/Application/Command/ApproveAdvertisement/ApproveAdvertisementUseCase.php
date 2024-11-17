<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\ApproveAdvertisement;

use Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement\DisableAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Command\EnableAdvertisement\EnableAdvertisementCommand;
use Demo\App\Advertisements\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Exception;

final class ApproveAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(ApproveAdvertisementCommand $command): void
    {
        // TODO: Implement user security

        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->advertisementId));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->advertisementId);
        }

        $advertisement->approve();

        $this->advertisementRepository->save($advertisement);
    }
}
