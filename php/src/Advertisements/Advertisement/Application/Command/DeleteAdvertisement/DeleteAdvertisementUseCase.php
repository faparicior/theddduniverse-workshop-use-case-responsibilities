<?php
declare(strict_types=1);

namespace Demo\App\Advertisements\Advertisement\Application\Command\DeleteAdvertisement;

use Demo\App\Advertisements\Advertisement\Domain\AdvertisementRepository;
use Demo\App\Advertisements\Advertisement\Domain\Services\AdvertisementSecurityService;
use Demo\App\Advertisements\Advertisement\Domain\ValueObjects\AdvertisementId;
use Demo\App\Advertisements\Shared\ValueObjects\UserId;
use Demo\App\Framework\Database\TransactionManager;
use Exception;

final class DeleteAdvertisementUseCase
{
    public function __construct(
        private AdvertisementRepository      $advertisementRepository,
        private AdvertisementSecurityService $securityService,
        private TransactionManager           $transactionManager,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(DeleteAdvertisementCommand $command): void
    {
        $this->transactionManager->beginTransaction();

        try{
            $advertisement = $this->advertisementRepository->findByIdOrFail(new AdvertisementId($command->advertisementId));

            $memberId = new UserId($command->securityUserId);

            $this->securityService->verifyMemberUserCanManageAdvertisement(
                $memberId,
                $advertisement,
            );

            $this->advertisementRepository->delete($advertisement);
        } catch (Exception $exception) {
            $this->transactionManager->rollback();
            throw $exception;
        }
    }
}
