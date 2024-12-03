<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\DisableAdvertisement;

use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Services\AdvertisementSecurityService;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Framework\Database\TransactionManager;
use Exception;

final class DisableAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository      $advertisementRepository,
        private AdvertisementSecurityService $securityService,
        private TransactionManager           $transactionManager,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(DisableAdvertisementCommand $command): void
    {
        $this->transactionManager->beginTransaction();

        try{
            $advertisement = $this->advertisementRepository->findByIdOrFail(new AdvertisementId($command->advertisementId));

            if ('admin' === $command->securityUserRole) {
                $this->securityService->verifyAdminUserCanManageAdvertisement(
                    new UserId($command->securityUserId),
                    $advertisement,
                );
            }

            if ('user' === $command->securityUserRole) {
                $this->securityService->verifyMemberUserCanManageAdvertisement(
                    new UserId($command->securityUserId),
                    $advertisement,
                );
            }

            $advertisement->disable();

            $this->advertisementRepository->save($advertisement);
        } catch (Exception $exception) {
            $this->transactionManager->rollback();
            throw $exception;
        }
    }
}
