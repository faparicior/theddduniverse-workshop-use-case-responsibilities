<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\UpdateAdvertisement;

use Demo\App\Advertisements\Advertisement\Application\Exceptions\InvalidPasswordException;
use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Services\AdvertisementSecurityService;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\Description;
use Demo\App\Advertisements\Shared\ValueObjects\Email;
use Demo\App\Advertisements\Shared\ValueObjects\Password;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Framework\Database\TransactionManager;
use Exception;

final class UpdateAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository      $advertisementRepository,
        private AdvertisementSecurityService $securityService,
        private TransactionManager           $transactionManager,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(UpdateAdvertisementCommand $command): void
    {
        $this->transactionManager->beginTransaction();

        try {
            $advertisement = $this->advertisementRepository->findByIdOrFail(new AdvertisementId($command->id));

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
        } catch (Exception $exception) {
            $this->transactionManager->rollback();
            throw $exception;
        }
    }
}
