<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\ApproveAdvertisement;

use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Services\AdvertisementSecurityService;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Framework\Database\TransactionManager;
use Exception;

final class ApproveAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository      $advertisementRepository,
        private AdvertisementSecurityService $securityService,
        private TransactionManager           $transactionManager,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(ApproveAdvertisementCommand $command): void
    {
        $this->transactionManager->beginTransaction();

        try {
            $advertisement = $this->advertisementRepository->findByIdOrFail(new AdvertisementId($command->advertisementId));

            $this->securityService->verifyAdminUserCanManageAdvertisement(
                new UserId($command->securityUserId),
                $advertisement,
            );

            $advertisement->approve();

            $this->advertisementRepository->save($advertisement);
            
            $this->transactionManager->commit();
        } catch (Exception $exception) {
            $this->transactionManager->rollback();
            throw $exception;
        }
    }
}
