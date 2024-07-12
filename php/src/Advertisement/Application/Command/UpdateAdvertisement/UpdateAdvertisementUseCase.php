<?php
declare(strict_types=1);

namespace Demo\App\Advertisement\Application\Command\UpdateAdvertisement;

use Demo\App\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisement\Domain\Exceptions\AdvertisementNotFoundException;
use Demo\App\Advertisement\Domain\Model\ValueObject\AdvertisementId;
use Demo\App\Advertisement\Domain\Model\ValueObject\Description;
use Demo\App\Advertisement\Domain\Model\ValueObject\Email;
use Demo\App\Advertisement\Domain\Model\ValueObject\Password;
use Exception;

final class UpdateAdvertisementUseCase
{
    public function __construct(private AdvertisementRepository $advertisementRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(UpdateAdvertisementCommand $command): void
    {
        $advertisement = $this->advertisementRepository->findById(new AdvertisementId($command->id));

        if (!$advertisement) {
            throw AdvertisementNotFoundException::withId($command->id);
        }

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
