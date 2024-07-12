<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\PublishAdvertisement;

use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Exceptions\AdvertisementAlreadyExistsException;
use Demo\App\Advertisement\Domain\Model\Advertisement;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementDate;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Description;
use Demo\App\Advertisement\Domain\Model\ValueObject\Email;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Exception;

final class PublishAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(PublishAdvertisementCommand $command): void
    {
        if ($this->advertisementRepository->findById(new AdvertisementId($command->id))) {
            throw AdvertisementAlreadyExistsException::withId($command->id);
        }

        $advertisement = new Advertisement(
            new AdvertisementId($command->id),
            new Description($command->description),
            new Email($command->email),
            Password::fromPlainPassword($command->password),
            new AdvertisementDate(new \DateTime()),
        );

        $this->advertisementRepository->save($advertisement);
    }
}
